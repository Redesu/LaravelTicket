import jdenticon from "jdenticon/standalone";
import { Modal } from 'bootstrap';

window.jdenticon = jdenticon;

$(function () {
    const modals = {
        editChamadosModal: document.getElementById('editChamadosModal') ? new Modal(document.getElementById('editChamadosModal')) : null,
        solucaoChamadoModal: document.getElementById('solucaoChamadoModal') ? new Modal(document.getElementById('solucaoChamadoModal')) : null
    };

    const $priorityBadge = $('#priority-badge');
    const priority = $priorityBadge.text().trim().toLowerCase();

    $priorityBadge.removeClass().addClass('badge');
    switch (priority.toLowerCase()) {
        case 'urgente':
            $priorityBadge.addClass('badge-danger');
            break;
        case 'alta':
            $priorityBadge.addClass('badge-warning');
            break;
        case 'média':
            $priorityBadge.addClass('badge-info');
            break;
        case 'baixa':
            $priorityBadge.addClass('badge-secondary');
            break;
        default:
            $priorityBadge.addClass('badge-secondary');
    }

    const $statusBadge = $('#status-badge');
    const status = $statusBadge.text().trim().toLowerCase();

    $statusBadge.removeClass().addClass('badge');
    switch (status) {
        case 'aberto':
            $statusBadge.addClass('badge-success');
            break;

        case 'em andamento':
            $statusBadge.addClass('badge-warning');
            break;

        case 'finalizado':
            $statusBadge.addClass('badge-secondary');
            break;
    }

    $(document).on('click', '.reply-btn', function () {
        showAddComment();
    });

    $(document).on('click', '.cancel-comment-btn', function () {
        cancelComment();
    })


    $(document).on('click', '.edit-btn', function () {
        if (modals.editChamadosModal) {
            modals.editChamadosModal.show();
        }
        console.log(window.chamado.status)

        $('#editChamadoId').val(window.chamado.id);
        $('#editChamadosTitulo').val(window.chamado.titulo);
        $('#editChamadosDescricao').val(window.chamado.descricao);
        $('#editChamadosStatus').val(window.chamado.status);
        $('#editChamadosPrioridade').val(window.chamado.prioridade);
        $('#editChamadosDepartamento').val(window.chamado.nome);
        $('#editChamadosCategoria').val(window.chamado.categoria);
        $('#editChamadosUsuario').val(window.chamado.usuario);
        $('#editChamadoForm').off('submit');
    });

    if (document.getElementById('solucaoChamadoModal')) {
        document.getElementById('solucaoChamadoModal').addEventListener('shown.bs.modal', function () {
            const $modal = $(this);
            const $dropZone = $modal.find('.drop-zone');
            const $fileInput = $modal.find('.file-input');
            let dragCounter = 0;

            $dropZone.off('click.solucaoModal dragenter.solucaoModal dragover.solucaoModal dragleave.solucaoModal drop.solucaoModal');
            $fileInput.off('change.solucaoModal');
            $(document).off('click.solucaoRemoveFile');

            $dropZone.on('click.solucaoModal', function (e) {
                if ($(e.target).hasClass('file-input') || $(e.target).closest('.file-input').length) {
                    return;
                }
                $fileInput.click();
            });

            $fileInput.on('change.solucaoModal', function () {
                handleFileSelect(this.files, $modal);
            });

            $dropZone.on('dragenter.solucaoModal', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dragCounter++;

                if (dragCounter === 1) {
                    $(this).addClass('drag-over');
                    if (navigator.vibrate) {
                        navigator.vibrate(50);
                    }
                }
            });

            $dropZone.on('dragover.solucaoModal', function (e) {
                e.preventDefault();
                e.stopPropagation();
            });

            $dropZone.on('dragleave.solucaoModal', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dragCounter--;

                if (dragCounter === 0) {
                    $(this).removeClass('drag-over');
                }
            });

            $dropZone.on('drop.solucaoModal', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dragCounter = 0;
                $(this).removeClass('drag-over');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    const dt = new DataTransfer();
                    Array.from(files).forEach(file => {
                        dt.items.add(file);
                    });
                    $fileInput[0].files = dt.files;

                    if (navigator.vibrate) {
                        navigator.vibrate([100, 50, 100]);
                    }

                    handleFileSelect(files, $modal);
                }
            });

            $(document).on('click.solucaoRemoveFile', '.remove-file', function () {
                if (!$modal.hasClass('show')) return;

                const $fileItem = $(this).closest('.selected-file-item');
                const indexToRemove = parseInt($(this).data('index'));
                const currentFiles = Array.from($fileInput[0].files);

                $fileItem.animate({
                    opacity: 0,
                    transform: 'translateX(100px)'
                }, 300, function () {
                    const dt = new DataTransfer();
                    currentFiles.forEach((file, index) => {
                        if (index !== indexToRemove) {
                            dt.items.add(file);
                        }
                    });

                    $fileInput[0].files = dt.files;
                    handleFileSelect($fileInput[0].files, $modal);
                });
            });
        });
    }

    $(document).on('click', '.solucao-btn', function () {
        if (modals.solucaoChamadoModal) {
            modals.solucaoChamadoModal.show();
        }
    });

    $(document).on('click', '.voltar-btn', function () {
        $('#voltarSpinner').removeClass('d-none');
        goBack();
    });


    $('#solucaoChamadoForm').on('submit', function (e) {
        e.preventDefault();
        const descricao = $('#solucaoDescricao').val().trim();

        if (!descricao) {
            showAlert('A descrição não pode estar vazia.', 'error');
            return;
        }

        $('#solucaoSpinner').removeClass('d-none');
        $('#solucaoSubmitBtn').prop('disabled', true);

        const formData = new FormData();
        formData.append('descricao', descricao);
        formData.append('tipo', 'solution');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        const fileInput = $('#solucaoChamadoModal .file-input')[0];
        if (fileInput && fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('anexos[]', fileInput.files[i]);
            }
        }

        $.ajax({
            url: window.routes.addSolution,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                if (modals.solucaoChamadoModal) {
                    modals.solucaoChamadoModal.hide();
                }
                showAlert('Solução adicionada com sucesso!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr) {
                showAlert('Ocorreu um erro ao adicionar a solução.', 'error');
            },
            complete: function () {
                $('#solucaoSpinner').addClass('d-none');
                $('#solucaoSubmitBtn').prop('disabled', false);
            }
        });
    });

    $(document).on('submit', '#add-comment-form', function (e) {
        e.preventDefault();

        const commentText = $('#comment-text').val().trim();
        const fileInput = $('#add-comment-card .file-input')[0];
        const spinner = $('#comentarioSpinner');
        const submitBtn = $('#addCommentBtn');

        if (!commentText) {
            showAlert('O comentário não pode estar vazio.', 'error');
            return;
        }

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        const originalBtnText = submitBtn.html();
        submitBtn.html('<span class="spinner-border spinner-border-sm" id="comentarioSpinner"></span> Enviando...');

        const formData = new FormData();
        formData.append('descricao', commentText);
        formData.append('tipo', 'comment');
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

        if (fileInput && fileInput.files.length > 0) {
            for (let i = 0; i < fileInput.files.length; i++) {
                formData.append('anexos[]', fileInput.files[i]);
            }
        }

        $.ajax({
            url: window.routes.addComment,
            type: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                cancelComment();
                showAlert('Comentário adicionado com sucesso!', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr, status, error) {
                spinner.addClass('d-none');
                showAlert('Erro ao adicionar comentário', 'error');
            },
            complete: function () {
                spinner.addClass('d-none');
                submitBtn.prop('disabled', false);
                submitBtn.html(originalBtnText);
            }
        });
    });

    $(document).on('click', '.download-btn', function (e) {
        e.preventDefault();

        const anexoId = $(this).data('anexo-id') ?? null;
        const filename = $(this).data('filename');
        const button = $(this);
        const originalHtml = button.html();

        button.prop('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: window.routes.anexosDownload.replace(':anexoId', anexoId),
            type: 'GET',
            xhrFields: {
                responseType: 'blob'
            },
            success: function (data, status, xhr) {
                const blob = new Blob([data]);
                const link = document.createElement('a');
                link.href = window.URL.createObjectURL(blob);
                link.download = filename;

                document.body.appendChild(link);
                link.click();
                document.body.removeChild(link);

                window.URL.revokeObjectURL(link.href);
            },
            error: function (xhr, status, error) {
                let errorMessage = 'Erro ao baixar o arquivo';

                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMessage = xhr.responseJSON.message;
                } else if (xhr.responseText) {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        errorMessage = response.message || errorMessage;
                    } catch (e) {
                        // If response is not JSON, use default message
                    }
                }

                showAlert(errorMessage, 'error');
            },
            complete: function () {
                button.prop('disabled', false);
                button.html(originalHtml);
            }
        });
    });

    $('#editChamadosForm').on('submit', function (e) {
        e.preventDefault();

        const submitBtn = $('#editSubmitBtn');
        const status = $('#editChamadosStatus');
        const spinner = $('#editSpinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        const formData = $(this).serialize();

        let updateUrl = window.routes.chamadosPut;
        let id = window.chamado.id;
        let finalUrl = updateUrl.replace(':id', id);

        $.ajax({
            url: finalUrl,
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            success: function (response) {
                if (typeof table !== 'undefined') {
                    table.ajax.reload();
                }
                if (modals.editChamadosModal) {
                    modals.editChamadosModal.hide();
                }
                showAlert('Chamado atualizado com sucesso', 'success');
                setTimeout(() => {
                    location.reload();
                }, 1500);
            },
            error: function (xhr, status, error) {
                showAlert(`Ocorreu um erro ao atualizar o chamado`, 'error');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    $('#add-comment-card').on('click', function () {
        const $card = $(this);
        const $dropZone = $card.find('.drop-zone');
        const $fileInput = $card.find('.file-input');
        let dragCounter = 0;

        $dropZone.off('click.commentCard dragenter.commentCard dragover.commentCard dragleave.commentCard drop.commentCard');
        $fileInput.off('change.commentCard');
        $(document).off('click.commentRemoveFile');

        $dropZone.on('click.commentCard', function (e) {
            if ($(e.target).hasClass('file-input') || $(e.target).closest('.file-input').length) {
                return;
            }
            $fileInput.click();
        });

        $fileInput.on('change.commentCard', function () {
            handleFileSelect(this.files, $card);
        });

        $dropZone.on('dragenter.commentCard', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter++;

            if (dragCounter === 1) {
                $(this).addClass('drag-over');
                if (navigator.vibrate) {
                    navigator.vibrate(50);
                }
            }
        });

        $dropZone.on('dragover.commentCard', function (e) {
            e.preventDefault();
            e.stopPropagation();
        });

        $dropZone.on('dragleave.commentCard', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter--;

            if (dragCounter === 0) {
                $(this).removeClass('drag-over');
            }
        });

        $dropZone.on('drop.commentCard', function (e) {
            e.preventDefault();
            e.stopPropagation();
            dragCounter = 0;
            $(this).removeClass('drag-over');

            const files = e.originalEvent.dataTransfer.files;
            if (files.length > 0) {
                const dt = new DataTransfer();
                Array.from(files).forEach(file => {
                    dt.items.add(file);
                });
                $fileInput[0].files = dt.files;

                if (navigator.vibrate) {
                    navigator.vibrate([100, 50, 100]);
                }

                handleFileSelect(files, $card);
            }
        });

        $(document).on('click.commentRemoveFile', '.remove-file', function () {
            if (!$card.is(':visible') || !$(this).closest('#add-comment-card').length) return;

            const $fileItem = $(this).closest('.selected-file-item');
            const indexToRemove = parseInt($(this).data('index'));
            const currentFiles = Array.from($fileInput[0].files);

            $fileItem.animate({
                opacity: 0,
                transform: 'translateX(100px)'
            }, 300, function () {
                const dt = new DataTransfer();
                currentFiles.forEach((file, index) => {
                    if (index !== indexToRemove) {
                        dt.items.add(file);
                    }
                });

                $fileInput[0].files = dt.files;
                handleFileSelect($fileInput[0].files, $card);
            });
        });
    });

    if (document.getElementById('solucaoChamadoModal')) {
        document.getElementById('solucaoChamadoModal').addEventListener('hidden.bs.modal', function () {
            const $modal = $(this);
            const $dropZone = $modal.find('.drop-zone');
            const $fileInput = $modal.find('.file-input');

            $dropZone.off('.solucaoModal');
            $fileInput.off('.solucaoModal');
            $(document).off('click.solucaoRemoveFile');

            $dropZone.removeClass('has-files drag-over');
            $modal.find('.drop-zone-text').html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
            $modal.find('.file-feedback').hide();
            $modal.find('.selected-files-container').hide();
            $modal.find('.selected-files-list').empty();
            resetModal('#solucaoChamadoForm', '#solucaoModalErrors');
        });
    }

    if (document.getElementById('editChamadosModal')) {
        document.getElementById('editChamadosModal').addEventListener('hidden.bs.modal', function () {
            resetModal('#editChamadosForm', '#editChamadosModalErrors');
        });
    }
    applyFileIcons();
});

function showAddComment() {
    const cardBody = $('#add-comment-card .card-body');
    if (cardBody.is(':hidden')) {
        cardBody.slideDown();
    }
    $('#comment-text').focus();
}

function cancelComment() {
    $('#comment-text').val('');
    $('#add-comment-card .card-body').slideUp();

    const $card = $('#add-comment-card');
    const $dropZone = $card.find('.drop-zone');
    const $fileInput = $card.find('.file-input');

    $dropZone.removeClass('has-files drag-over');
    $card.find('.drop-zone-text').html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
    $card.find('.selected-files-container').hide();
    $card.find('.selected-files-list').empty();
    if ($fileInput[0]) $fileInput[0].files = new DataTransfer().files;
}

function goBack() {
    window.history.back();
}

function getFileIcon(fileExtension) {
    const iconMap = {
        'pdf': 'fa-file-pdf text-danger',
        'jpg': 'fa-file-image text-info',
        'jpeg': 'fa-file-image text-info',
        'png': 'fa-file-image text-info',
        'zip': 'fa-file-archive text-warning',
        'rar': 'fa-file-archive text-warning',
        'mp4': 'fa-file-video text-info',
    };

    return iconMap[fileExtension.toLowerCase()] || 'fa-file text-secondary';
}

function formatFileSize(bytes) {
    if (bytes == 0) return '0 Bytes';

    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));

    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

function applyFileIcons() {
    $('.file-icon[data-file-type]').each(function () {
        const $icon = $(this);
        const fileExtension = $icon.data('file-type');

        // Use your existing getFileIcon function
        const iconClasses = getFileIcon(fileExtension);

        // Remove the default fa-file class and apply the new classes
        $icon.removeClass('fa-file').addClass(iconClasses);
    });
}

function getFileColor(fileType) {
    switch (fileType) {
        case 'application/pdf':
            return '#dc3545';
        case 'image/jpeg':
        case 'image/png':
            return '#28a745';
        case 'application/zip':
        case 'application/x-rar-compressed':
            return '#ffc107';
        case 'video/mp4':
            return '#6f42c1';
        default:
            return '#6c757d';
    }
}

function handleFileSelect(files, $container) {
    const $dropZoneText = $container.find('.drop-zone-text');
    const $dropZone = $container.find('.drop-zone');
    const $selectedFilesContainer = $container.find('.selected-files-container');
    const $selectedFilesList = $container.find('.selected-files-list');

    if (files.length > 0) {
        const validTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/zip', 'application/x-rar-compressed', 'video/mp4'];
        const maxSize = 150 * 1024 * 1024; // 150MB
        const validFiles = [];
        const invalidFiles = [];

        Array.from(files).forEach(file => {
            if (!validTypes.includes(file.type)) {
                invalidFiles.push(`${file.name} - tipo inválido`);
            } else if (file.size > maxSize) {
                invalidFiles.push(`${file.name} - muito grande (${(file.size / (1024 * 1024)).toFixed(1)}MB)`);
            } else {
                validFiles.push(file);
            }
        });

        if (invalidFiles.length > 0) {
            showAlert(`Arquivos inválidos: ${invalidFiles.join(', ')}. Tipos permitidos: JPG, PNG, PDF, ZIP, RAR, MP4 (máx 150MB cada).`, 'error');
        }

        if (validFiles.length > 0) {
            $dropZone.addClass('has-files');

            if (validFiles.length === 1) {
                $dropZoneText.html(`<i class="fas fa-check-circle"></i> ${validFiles[0].name}`);
            } else {
                $dropZoneText.html(`<i class="fas fa-check-circle"></i> ${validFiles.length} arquivos selecionados`);
            }

            $selectedFilesList.empty();
            validFiles.forEach((file, index) => {
                const fileSize = (file.size / (1024 * 1024)).toFixed(1);
                const fileExtension = file.name.split('.').pop();
                const fileIcon = getFileIcon(fileExtension);
                const fileItem = $(`
    <div class="selected-file-item d-flex justify-content-between align-items-center p-2 mb-1 bg-light rounded" style="opacity: 0; transform: translateY(20px);">
        <span class="file-info">
            <i class="fas ${fileIcon} mr-2" style="color: ${getFileColor(file.type)};"></i>
            <strong>${file.name}</strong>
            <small class="text-muted">(${fileSize}MB)</small>
        </span>
        <button type="button" class="btn btn-sm btn-outline-danger remove-file" data-index="${index}">
            <i class="fas fa-times"></i>
        </button>
    </div>
    `);

                $selectedFilesList.append(fileItem);

                setTimeout(() => {
                    fileItem.animate({
                        opacity: 1,
                        transform: 'translateY(0)'
                    }, 300);
                }, index * 100);
            });

            $selectedFilesContainer.slideDown(400);
            showAlert(`${validFiles.length} arquivo(s) válido(s) selecionado(s)`, 'success');
        } else {
            $dropZone.removeClass('has-files');
            $dropZoneText.html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
            $selectedFilesContainer.slideUp(400);
        }
    } else {
        $dropZone.removeClass('has-files');
        $dropZoneText.html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
        $selectedFilesContainer.slideUp(400);
    }
}