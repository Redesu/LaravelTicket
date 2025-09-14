@push('js')

<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="{{ asset('js/FloatingActionButton.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/jdenticon@3.3.0/dist/jdenticon.min.js" async
    integrity="sha384-LfouGM03m83ArVtne1JPk926e3SGD0Tz8XHtW2OKGsgeBU/UfR0Fa8eX+UlwSSAZ" crossorigin="anonymous">
    </script>



<script>
    $(document).ready(function () {
        const $priorityBadge = $('#priority-badge');
        const priority = $priorityBadge.text().trim().toLowerCase();

        $priorityBadge.removeClass().addClass('badge'); // Reset classes
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

        $statusBadge.removeClass().addClass('badge'); // Reset classes
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


        $(document).on('click', '.edit-btn', function () {
            $('#editChamadosModal').modal('show');
            console.log('{{ $chamado->status }}')

            $('#editChamadoId').val({{ $chamado->id }});
            $('#editChamadosTitulo').val('{{ $chamado->titulo }}');
            $('#editChamadosDescricao').val('{{ $chamado->descricao }}');
            $('#editChamadosStatus').val('{{ $chamado->status }}');
            $('#editChamadosPrioridade').val('{{ $chamado->prioridade }}');
            $('#editChamadosDepartamento').val('{{ $chamado->departamento->nome }}');
            $('#editChamadosCategoria').val('{{ $chamado->categoria->nome }}');
            $('#editChamadosUsuario').val('{{ $chamado->usuario->id }}');
            $('#editChamadoForm').off('submit');
        });

        $(document).on('click', '.solucao-btn', function () {
            $('#solucaoChamadoModal').modal('show');
            // $('#solucaoChamadoForm').off('submit');
        });

        $(document).on('click', '.voltar-btn', function () {
            $('#voltarSpinner').removeClass('d-none');
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

            $.ajax({
                url: '{{ route("api.chamados.addSolution", $chamado->id) }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: {
                    descricao: descricao,
                    tipo: 'solution',
                },
                success: function (response) {
                    $('#solucaoChamadoModal').modal('hide');
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
            const fileInput = $('#anexo')[0];
            const spinner = $('#comentarioSpinner');
            const submitBtn = $('#addCommentBtn');

            if (!commentText) {
                showAlert('O comentário não pode estar vazio.', 'error');
                return;
            }

            // Show loading state
            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            const originalBtnText = submitBtn.html();
            submitBtn.html('<span class="spinner-border spinner-border-sm" id="comentarioSpinner"></span> Enviando...');

            // create formData object

            const formData = new FormData();
            formData.append('descricao', commentText);
            formData.append('tipo', 'comment');
            formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

            if (fileInput.files.length > 0) {
                for (let i = 0; i < fileInput.files.length; i++) {
                    formData.append('anexos[]', fileInput.files[i]);
                }
            }
            console.log(formData);

            $.ajax({
                url: '{{ route("api.chamados.addComentario", $chamado->id) }}',
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

            // Show loading state
            button.prop('disabled', true);
            button.html('<i class="fas fa-spinner fa-spin"></i>');

            $.ajax({
                url: `{{ route('api.anexos.download', ':anexoId') }}`,
                type: 'GET',
                xhrFields: {
                    responseType: 'blob' // Important for file downloads
                },
                success: function (data, status, xhr) {
                    // Create blob link to download
                    const blob = new Blob([data]);
                    const link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;

                    // Trigger download
                    document.body.appendChild(link);
                    link.click();
                    document.body.removeChild(link);

                    // Clean up
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
                    // Restore button state
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

            let updateUrl = "{{ route('api.chamados.put', ':id') }}";
            let id = {{ $chamado->id }};
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
                    $('#editChamadosModal').modal('hide');
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
            const dropZone = $('#anexoDropZone');
            const fileInput = $('#anexo');
            let dragCounter = 0;

            dropZone.off('click.fileUpload dragenter.fileUpload dragover.fileUpload dragleave.fileUpload drop.fileUpload');
            fileInput.off('change.fileUpload');
            $(document).off('click.removeFile');

            dropZone.on('click.fileUpload', function (e) {
                if (e.target !== fileInput[0]) {
                    fileInput.click();
                }
            });

            fileInput.on('change.fileUpload', function () {
                handleFileSelect(this.files);
            });

            dropZone.on('dragenter.fileUpload', function (e) {
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

            dropZone.on('dragover.fileUpload', function (e) {
                e.preventDefault();
                e.stopPropagation();
            });

            dropZone.on('dragleave.fileUpload', function (e) {
                e.preventDefault();
                e.stopPropagation();
                dragCounter--;

                if (dragCounter === 0) {
                    $(this).removeClass('drag-over');
                }
            });

            dropZone.on('drop.fileUpload', function (e) {
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
                    fileInput[0].files = dt.files;

                    if (navigator.vibrate) {
                        navigator.vibrate([100, 50, 100]);
                    }

                    handleFileSelect(files);
                }
            });

            $(document).on('click.removeFile', '.remove-file', function () {
                const $fileItem = $(this).closest('.selected-file-item');
                const indexToRemove = parseInt($(this).data('index'));
                const currentFiles = Array.from(fileInput[0].files);

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

                    fileInput[0].files = dt.files;
                    handleFileSelect(fileInput[0].files);
                });
            });
        });


        $('#solucaoChamadoModal').on('hidden.bs.modal', function () {
            resetModal('#solucaoChamadoForm', '#solucaoModalErrors');
        });

        $('#editChamadosModal').on('hidden.bs.modal', function () {
            resetModal('#editChamadosForm', '#editChamadosModalErrors');
        });
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
    }


    function showAlert(message, type) {
        if (typeof toastr !== 'undefined') {
            const validTypes = ['success', 'error', 'warning', 'info'];
            const toastrType = validTypes.includes(type) ? type : 'info';

            toastr[toastrType](message);
        } else {
            alert(message);
        }
    }

    function resetModal(formSelector) {
        $(formSelector)[0].reset();
        $(formSelector + ' button[type="submit"]').prop('disabled', false);
        $(formSelector + ' .spinner-border').addClass('d-none');
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


    function initializeFileDisplay() {
        document.querySelectorAll('.file-icon').forEach(function (icon) {
            const fileType = icon.getAttribute('data-file-type');
            const iconClasses = getFileIcon(fileType);

            icon.className = icon.className.replace(/fa-file[\w-]*/g, '').trim();
            icon.className += ' ' + iconClasses;
        });

        // Update file sizes
        document.querySelectorAll('.file-size').forEach(function (sizeElement) {
            const bytes = parseInt(sizeElement.getAttribute('data-size'));
            sizeElement.textContent = formatFileSize(bytes);
        });
    }

    // Initialize when DOM is loaded
    document.addEventListener('DOMContentLoaded', function () {
        initializeFileDisplay();
    });

    // Also initialize when new comments are loaded via AJAX (if applicable)
    function reinitializeFileDisplay() {
        initializeFileDisplay();
    }

    function handleFileSelect(files) {
        const dropZoneText = $('#dropZoneText');
        const dropZone = $('#anexoDropZone');
        const selectedFilesContainer = $('#selectedFilesContainer');
        const selectedFilesList = $('#selectedFilesList');

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
                dropZone.addClass('has-files');

                if (validFiles.length === 1) {
                    dropZoneText.html(`<i class="fas fa-check-circle"></i> ${validFiles[0].name}`);
                } else {
                    dropZoneText.html(`<i class="fas fa-check-circle"></i> ${validFiles.length} arquivos selecionados`);
                }

                selectedFilesList.empty();
                validFiles.forEach((file, index) => {
                    const fileSize = (file.size / (1024 * 1024)).toFixed(1);
                    const fileExtension = file.name.split('.').pop();
                    console.log(fileExtension);
                    const fileIcon = getFileIcon(fileExtension);
                    console.log(fileIcon);
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

                    selectedFilesList.append(fileItem);

                    setTimeout(() => {
                        fileItem.animate({
                            opacity: 1,
                            transform: 'translateY(0)'
                        }, 300);
                    }, index * 100);
                });

                selectedFilesContainer.slideDown(400);
                showAlert(`${validFiles.length} arquivo(s) válido(s) selecionado(s)`, 'success');
            } else {
                dropZone.removeClass('has-files');
                dropZoneText.html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
                selectedFilesContainer.slideUp(400);
            }
        } else {
            dropZone.removeClass('has-files');
            dropZoneText.html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
            selectedFilesContainer.slideUp(400);
        }
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


</script>