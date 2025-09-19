@push('js')

<script>
    $(document).ready(function () {

        var table = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.departamentos.data-tables') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (xhr, error, thrown) {
                    showAlert(`Erro ao obter os departamentos`, 'error');
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
                { data: 'descricao', name: 'descricao' },
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
                        actions += `<button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}" data-bs-toggle="modal" data-bs-target="#editDepartamentoModal"><i class="fas fa-edit"></i> Editar</button> `;
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
                    url: "{{ route('api.departamentos.delete') }}",
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: { "id": id },
                    success: function (result) {
                        table.ajax.reload();
                        showAlert('Departamento excluído com sucesso', 'success');
                    },
                    error: function (xhr, status, error) {
                        var response = xhr.responseJSON;
                        var errorMessage = 'Erro ao excluir o departamento';

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

            $('#editDepartamentoId').val(rowData.id);
            $('#DepartamentoNome').val(rowData.nome);
            $('#editDepartamentoDescricao').val(rowData.descricao);
            $('#editDepartamentoModal').modal('show');
        });

        $('#createDepartamentoForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#submitBtn');
            const spinner = $('#spinner');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            console.log($(this).serialize());

            $.ajax({
                url: "{{ route('api.departamentos.post') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: $(this).serialize(),
                success: function (response) {
                    table.ajax.reload();
                    $('#createDepartamentoModal').modal('hide');
                    showAlert('Departamento criado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert('Erro ao criar o departamento', 'error');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            })
        });

        $('#editDepartamentoForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#editSubmitBtn');
            const spinner = $('#editSpinner');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');

            let updateUrl = "{{ route('api.departamentos.put', ':id') }}";
            let id = $('#editDepartamentoId').val();
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
                    $('#editDepartamentoModal').modal('hide');
                    showAlert('Departamento atualizado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert('Erro ao atualizar o departamento', 'error');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $('#createDepartamentoModal').on('hidden.bs.modal', function () {
            resetModal('#createDepartamentoForm');
        });

        $('#editDepartamentoModal').on('hidden.bs.modal', function () {
            resetModal('#editDepartamentoForm');
        });
    });

</script>