import $ from 'jquery';
import 'bootstrap';
import { Modal } from 'bootstrap';
import showAlert from './AppUtils.js';

window.$ = window.jQuery = $;

$(function () {

    const currentUserId = window.currentUserId;

    const modals = {
        createChamadoModal: new Modal(document.getElementById('createChamadoModal')),
        editChamadosModal: new Modal(document.getElementById('editChamadosModal')),
        filtrarChamadosModal: new Modal(document.getElementById('filtrarChamadosModal'))
    };

    var table = $('#dataTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: window.routes.chamadosDataTable,
            data: function (d) {
                d.status = $('#status').val();
                d.prioridade = $('#filtrarChamadosPrioridade').val();
                d.user_id = $('#filtrarChamadosUsuario').val();
                d.departamento = $('#filtrarChamadosDepartamento').val();
                d.categoria = $('#filtrarChamadosCategoria').val();
                d.created_at_inicio = $('#created_at_inicio').val();
                d.created_at_fim = $('#created_at_fim').val();
                d.updated_at_inicio = $('#updated_at_inicio').val();
                d.updated_at_fim = $('#updated_at_fim').val();
            },
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (xhr, error, thrown) {
                showAlert(`Erro ao obter os chamados`, 'error');
            }
        },
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'titulo', name: 'titulo' },
            { data: 'descricao', name: 'descricao' },
            { data: 'status', name: 'status' },
            {
                data: null,
                name: 'depar_prior',
                title: 'Depar/Prior',
                render: function (data, type, row) {
                    const departamento = row.departamento || '';
                    const prioridade = row.prioridade || '';

                    let priorityBadgeClass = '';
                    switch (prioridade.toLowerCase()) {
                        case 'urgente':
                            priorityBadgeClass = 'badge-danger';
                            break;
                        case 'alta':
                            priorityBadgeClass = 'badge-warning';
                            break;
                        case 'média':
                            priorityBadgeClass = 'badge-info';
                            break;
                        case 'baixa':
                            priorityBadgeClass = 'badge-secondary';
                            break;
                        default:
                            priorityBadgeClass = 'badge-secondary';
                    }

                    return `
    <div class="d-flex align-items-center">
        <small class="text-muted me-2">${departamento}</small>
        <span class="badge ${priorityBadgeClass}">${prioridade}</span>
    </div>
    `;
                },
                orderable: false,
                searchable: false
            },
            { data: 'categoria', name: 'categoria' },
            { data: 'usuario_id', name: 'usuario_id' },
            {
                data: 'data_abertura',
                name: 'data_abertura',
                render: function (data, type, row) {
                    if (data && data !== null) {
                        return new Date(data).toLocaleString('pt-BR', {
                            year: 'numeric',
                            month: '2-digit',
                            day: '2-digit',
                            hour: '2-digit',
                            minute: '2-digit'
                        });
                    }
                    return '';
                }
            },
            {
                data: null,
                name: 'actions',
                render: function (data, type, row) {
                    let actions = '';
                    if (currentUserId) {
                        actions += `<button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}"><i class="fas fa-edit"></i> Editar</button> `;
                        actions += `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i> Excluir</button>`;
                    }
                    return actions;
                },
                orderable: false,
                searchable: false
            },
        ],
        autoWidth: false,
        columnDefs: [
            {
                targets: 3,
                render: function (data, type, row) {
                    let badgeClass = '';
                    switch (data) {
                        case 'Aberto':
                            badgeClass = 'badge-success';
                            break;
                        case 'Em andamento':
                            badgeClass = 'badge-warning';
                            break;
                        case 'Finalizado':
                            badgeClass = 'badge-secondary';
                            break;
                        default:
                            badgeClass = 'badge-secondary';
                    }
                    return `<span class="badge ${badgeClass}">${data}</span>`;
                }
            },
            {
                targets: 6,
                visible: false,
                searchable: false
            }
        ],
        pageLength: 15,
        lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Todos"]],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
            processing: `
            <div class="custom-processing">
                <div class="dataTables_spinner"></div>
                <span>Carregando dados...</span>
            </div>
        `
        },
    });

    $('#dataTable tbody').on('click', 'tr', function (e) {
        if ($(e.target).closest('button').length) {
            return;
        }

        var data = table.row(this).data();
        if (data) {
            window.location.href = window.routes.chamadosShow.replace(':id', data.id);
        }
    });

    $('#dataTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        if (confirm('Tem certeza que deseja excluir este chamado?')) {
            $.ajax({
                url: window.routes.chamadosDelete,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { "id": id },
                success: function (result) {
                    table.ajax.reload();
                    showAlert('Chamado excluído com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var errorMessage = 'Erro ao excluir o chamado';

                    if (response && response.message) {
                        errorMessage = response.message;
                    }

                    showAlert(errorMessage, 'error');
                }
            });
        }
    });


    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const rowData = table.row($(this).closest('tr')).data();

        $('#editChamadoId').val(rowData.id);
        $('#editChamadosTitulo').val(rowData.titulo);
        $('#editChamadosStatus').val(rowData.status);
        $('#editChamadosDescricao').val(rowData.descricao);
        $('#editChamadosPrioridade').val(rowData.prioridade);
        $('#editChamadosDepartamento').val(rowData.departamento_id || rowData.departamento);
        $('#editChamadosCategoria').val(rowData.categoria_id || rowData.categoria);
        $('#editChamadosUsuario').val(rowData.usuario_id || rowData.user_id);
        $('#editChamadosForm').off('submit'); // Remove previous submit handler

        modals.editChamadosModal.show();
    });

    $(document).on('click', '.create-btn', function () {
        modals.createChamadoModal.show();
    })

    document.getElementById('createChamadoModal').addEventListener('shown.bs.modal', function () {
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

    $('#clearFilters').on('click', function () {
        $(this).blur();

        $('#filtrarChamadosForm')[0].reset();

        $('#filtrarChamadosForm select').each(function () {
            $(this).val('');
        });

        $('#filtrarChamadosForm input[type="date"]').each(function () {
            $(this).val('');
        });

        table.ajax.reload();

        modals.filtrarChamadosModal.hide();
        showAlert('Filtros limpos com sucesso', 'success');
    })

    $('#createChamadoForm').on('submit', function (e) {
        e.preventDefault();
        const submitBtn = $('#submitBtn');
        const spinner = $('#spinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        const formData = new FormData(this);

        $.ajax({
            url: window.routes.chamadosPost,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                table.ajax.reload();
                modals.createChamadoModal.hide();
                showAlert('Chamado criado com sucesso', 'success');
            },
            error: function (xhr, status, error) {
                showAlert('Erro ao criar o chamado', 'error');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    $(document).on('submit', '#editChamadosForm', function (e) {
        e.preventDefault();

        const submitBtn = $('#editSubmitBtn');
        const status = $('#editChamadosStatus');
        const spinner = $('#editSpinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        status.prop('disabled', false);
        const formData = $(this).serialize();
        status.prop('disabled', true);

        let updateUrl = window.routes.chamadosPut;
        let id = $('#editChamadoId').val();
        let finalUrl = updateUrl.replace(':id', id);


        $.ajax({
            url: finalUrl,
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: formData,
            success: function (response) {
                table.ajax.reload();
                modals.editChamadosModal.hide();
                showAlert('Chamado atualizado com sucesso', 'success');
            },
            error: function (xhr, status, error) {
                showAlert('Erro ao atualizar o chamado', 'error');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    $(document).on('submit', '#filtrarChamadosForm', function (e) {
        e.preventDefault();
        const submitBtn = $('#filtrarSubmitBtn');
        const spinner = $('#filtrarSpinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        table.ajax.reload(function (json) {
            modals.filtrarChamadosModal.hide();

            showAlert('Filtros aplicados com sucesso', 'success');

            resetModal('#filtrarChamadosForm');
        })
    });

    $('#createChamadoModal').on('hidden.bs.modal', function () {
        const dropZone = $('#anexoDropZone');
        const fileInput = $('#anexo');

        dropZone.off('.fileUpload');
        fileInput.off('.fileUpload');
        $(document).off('click.removeFile');

        dropZone.removeClass('has-files drag-over');
        $('#dropZoneText').html('<i class="fas fa-cloud-upload-alt"></i> Arraste e solte os arquivos aqui ou clique para selecionar');
        $('#anexo-feedback').hide();
        $('#selectedFilesContainer').hide();
        $('#selectedFilesList').empty();
        resetModal('#createChamadoForm');
    });

    document.getElementById('editChamadosModal').addEventListener('hidden.bs.modal', function () {
        resetModal('#editChamadosForm');
    });

    document.getElementById('filtrarChamadosModal').addEventListener('hidden.bs.modal', function () {
        resetModal('#filtrarChamadosForm');
    });
});

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
                const fileIcon = getFileIcon(file.type);
                const fileItem = $(`
        <div class="selected-file-item d-flex justify-content-between align-items-center p-2 mb-1 bg-light rounded" style="opacity: 0; transform: translateY(20px);">
            <span class="file-info">
                <i class="${fileIcon} mr-2" style="color: ${getFileColor(file.type)};"></i>
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

function getFileIcon(fileType) {
    switch (fileType) {
        case 'application/pdf':
            return 'fas fa-file-pdf';
        case 'image/jpeg':
        case 'image/png':
            return 'fas fa-file-image';
        case 'application/zip':
        case 'application/x-rar-compressed':
            return 'fas fa-file-archive';
        case 'video/mp4':
            return 'fas fa-file-video';
        default:
            return 'fas fa-file';
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