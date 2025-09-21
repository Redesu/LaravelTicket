import { Modal } from "bootstrap";
import $ from 'jquery';
import 'bootstrap';

window.$ = window.jQuery = $;

$(function () {

    const modals = {
        createCategoriaModal: new Modal(document.getElementById('createCategoriaModal')),
        editCategoriaModal: new Modal(document.getElementById('editCategoriaModal'))
    };

    var table = $('#dataTable').DataTable({
        responsive: true,
        processing: true,
        serverSide: true,
        ajax: {
            url: window.routes.categoriasDataTable,
            type: "GET",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            error: function (xhr, error, thrown) {
                showAlert(`Erro ao obter as categorias`, 'error');
            }
        },
        layout: {
            topStart: {
                buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5', 'pdfHtml5']
            }
        },
        columns: [
            { data: 'id', name: 'id' },
            { data: 'nome', name: 'nome' },
            {
                data: 'criado_em',
                name: 'criado_em',
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
                    actions += `<button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editCategoriaModal"><i class="fas fa-edit"></i> Editar</button> `;
                    actions += `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i> Excluir</button>`;
                    return actions;
                },
                orderable: false,
                searchable: false
            },
        ],
        autoWidth: false,
        pageLength: 15,
        lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Todos"]],
        language: {
            url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
            processing: '<i class="fas fa-spinner fa-spin fa-2x"></i><br>Carregando dados...'
        },
    });

    $('#dataTable').on('click', '.delete-btn', function () {
        var id = $(this).data('id');
        if (confirm('Tem certeza que deseja excluir este chamado?')) {
            $.ajax({
                url: window.routes.categoriasDelete,
                type: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: { "id": id },
                success: function (result) {
                    table.ajax.reload();
                    showAlert('Categoria excluída com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    var response = xhr.responseJSON;
                    var errorMessage = 'Erro ao excluir a categoria';

                    if (response && response.message) {
                        errorMessage = response.message;
                    }

                    showAlert(errorMessage, 'error');
                }
            });
        }
    });

    $(document).on('click', '.create-btn', function () {
        modals.createCategoriaModal.show();
    });

    $(document).on('click', '.edit-btn', function () {
        const id = $(this).data('id');
        const rowData = table.row($(this).closest('tr')).data();

        $('#editCategoriaId').val(rowData.id);
        $('#categoriaNome').val(rowData.nome);
        modals.editCategoriaModal.show();
    });

    $('#createCategoriaForm').on('submit', function (e) {
        e.preventDefault();

        const submitBtn = $('#submitBtn');
        const spinner = $('#spinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        $.ajax({
            url: window.routes.categoriasPost,
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            data: $(this).serialize(),
            success: function (response) {
                table.ajax.reload();
                modals.createCategoriaModal.hide();
                showAlert('Categoria criada com sucesso', 'success');
            },
            error: function (xhr, status, error) {
                showAlert('Erro ao criar a categoria', 'error');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        })
    });

    $('#editCategoriaForm').on('submit', function (e) {
        e.preventDefault();

        const submitBtn = $('#editSubmitBtn');
        const spinner = $('#editSpinner');

        submitBtn.prop('disabled', true);
        spinner.removeClass('d-none');

        let updateUrl = window.routes.categoriasPut;
        let id = $('#editCategoriaId').val();
        let finalUrl = updateUrl.replace(':id', id);

        $.ajax({
            url: finalUrl,
            method: "PUT",
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            },
            data: $(this).serialize(),
            success: function (response) {
                table.ajax.reload();
                modals.editCategoriaModal.hide();
                showAlert('Categoria atualizada com sucesso', 'success');
            },
            error: function (xhr, status, error) {
                showAlert('Erro ao atualizar a categoria', 'error');
            },
            complete: function () {
                submitBtn.prop('disabled', false);
                spinner.addClass('d-none');
            }
        });
    });

    $('#createCategoriaModal').on('hidden.bs.modal', function () {
        resetModal('#createCategoriaForm');
    });

    $('#editCategoriaModal').on('hidden.bs.modal', function () {
        resetModal('#editCategoriaForm');
    });
});
