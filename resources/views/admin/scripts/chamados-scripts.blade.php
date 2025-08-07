@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    $(document).ready(function () {
        console.log('Initializing DataTables...');

        const currentUserId = {{ auth()->user()->id ?? 'null'}};

        var table = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.chamados.get') }}",
                type: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                error: function (xhr, error, thrown) {
                    console.log('AJAX Error:', xhr, error, thrown);
                    console.log('Response Text:', xhr.responseText);
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'titulo', name: 'titulo' },
                { data: 'descricao', name: 'descricao' },
                { data: 'status', name: 'status' },
                { data: 'prioridade', name: 'prioridade' },
                { data: 'categoria', name: 'categoria' },
                { data: 'departamento', name: 'departamento' },
                {
                    data: 'data_abertura',
                    name: 'data_abertura',
                    render: function (data, type, row) {
                        if (data && data !== null) {
                            return new Date(data).toLocaleDateString('pt-BR');
                        }
                        return '';
                    }
                },
                {
                    data: null,
                    name: 'actions',
                    render: function (data, type, row) {
                        let actions = `<button class="btn btn-info btn-sm view-btn" data-id="${row.id}"><i class="fas fa-eye"></i> Ver</button> `;
                        if (currentUserId) {
                            actions += `<button class="btn btn-primary btn-sm edit-btn" data-id="${row.id}"><i class="fas fa-edit"></i> Editar</button> `;
                            actions += `<button class="btn btn-danger btn-sm delete-btn" data-id="${row.id}"><i class="fas fa-trash"></i> Excluir</button>`;
                        }
                        return actions;
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            pageLength: 15,
            lengthMenu: [[15, 25, 50, -1], [15, 25, 50, "Todos"]],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
            },
            initComplete: function () {
                console.log('DataTables initialized successfully');
            },
            drawCallback: function (settings) {
                console.log('DataTables draw completed, rows:', settings.fnRecordsDisplay());
            }
        });

        $('#dataTable').on('click', '.edit-btn', function () {
            var id = $(this).data('id');
            var rowData = table.row($(this).closest('tr')).data();
            console.log('Edit button clicked for ID:', id, rowData);
        });

        $('#dataTable').on('click', '.view-btn', function () {
            var id = $(this).data('id');
            var rowData = table.row($(this).closest('tr')).data();
            console.log('View button clicked for ID:', id, rowData);
        });

        $('#dataTable').on('click', '.delete-btn', function () {
            var id = $(this).data('id');
            if (confirm('Tem certeza que deseja excluir este chamado?')) {
                $.ajax({
                    url: "{{ url('api.chamados.delete') }}",
                    type: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function (result) {
                        table.ajax.reload();
                        showAlert('Chamado excluído com sucesso', 'success');
                    },
                    error: function (xhr, status, error) {
                        console.log('Delete error:', xhr, status, error);
                        showAlert('Erro ao excluir o chamado', 'danger');
                    }
                });
            }
        });

        $('#refreshBtn').on('click', function () {
            table.ajax.reload();
            showAlert('Grid recarregado com sucesso', 'success');
        });

        $('#exportBtn').on('click', function () {
            $.ajax({
                url: "{{ route('api.chamados.get') }}",
                method: "GET",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function (data) {
                    let csv = 'ID;Título;Descrição;Status;Prioridade;Categoria;Departamento;Data Abertura\n';

                    const records = data.data || data;

                    records.forEach(function (chamado) {
                        csv += `${chamado.id};${chamado.titulo};${chamado.descricao};${chamado.status};${chamado.prioridade};${chamado.categoria || chamado.categoria_id};${chamado.departamento || chamado.departamento_id};${chamado.data_abertura || chamado.created_at}\n`;
                    });

                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'chamados.csv';
                    document.body.appendChild(a);
                    a.click();
                    document.body.removeChild(a);
                    URL.revokeObjectURL(url);
                    showAlert('Dados exportados com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    console.log('Export error:', xhr, status, error);
                    showAlert('Erro ao exportar os dados', 'danger');
                }
            });
        });

        $('#createChamadoForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#submitBtn');
            const spinner = $('#spinner');
            const errorDiv = $('#modalErrors');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            errorDiv.addClass('d-none');

            $.ajax({
                url: "{{ route('api.chamados.post') }}",
                method: "POST",
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                },
                data: JSON.stringify($(this).serialize()),
                success: function (response) {
                    table.ajax.reload();
                    $('#createChamadoModal').modal('hide');
                    showAlert('Chamado criado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    console.log('Create error:', xhr, status, error);
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMsg = '<ul>';
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            errorMsg += '<li>' + value + '</li>';
                        });
                        errorMsg += '</ul>';
                        errorDiv.html(errorMsg);
                        errorDiv.removeClass('d-none');
                    }
                    showAlert('Erro ao criar o chamado', 'danger');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $(document).on('click', '.edit-btn', function () {
            const userId = $(this).data('id');
            console.log("Submitting create form to ", "{{ route('api.chamados.post') }}");
            console.log('Form data:', $(this).serialize());


            $.ajax({
                url: "{{ url('api.chamados.put') }}",
                method: "GET",
                success: function (response) {
                    $('#editChamadoId').val(response.data.id);
                    $('#editTitulo').val(response.data.titulo);
                    $('#editDescricao').val(response.data.descricao);
                    $('#editStatus').val(response.data.status);
                    $('#editPrioridade').val(response.data.prioridade);
                    $('#editCategoria').val(response.data.categoria_id);
                    $('#editDepartamento').val(response.data.departamento_id);
                    $('#editChamadosModal').modal('show');
                },
                error: function (xhr, status, error) {
                    console.log('Edit error:', xhr, status, error);
                    showAlert('Erro ao carregar os dados para edição', 'danger');
                }
            });
        });

        $('#editChamadosForm').on('submit', function (e) {
            e.preventDefault();

            const submitBtn = $('#editSubmitBtn');
            const spinner = $('#editSpinner');
            const errorDiv = $('#editModalErrors');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            errorDiv.addClass('d-none');

            $.ajax({
                url: "{{ url('api.chamados.put', ['id' => '']) }}/" + $('#editChamadoId').val(),
                method: "PUT",
                data: $(this).serialize(),
                success: function (response) {
                    table.ajax.reload();
                    $('#editChamadosModal').modal('hide');
                    showAlert('Chamado atualizado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    console.log('Update error:', xhr, status, error);
                    errorDiv.html(xhr.responseJSON.errors);
                    errorDiv.removeClass('d-none');
                    showAlert('Erro ao atualizar o chamado', 'danger');
                },
                complete: function () {
                    submitBtn.prop('disabled', false);
                    spinner.addClass('d-none');
                }
            });
        });

        $(document).on('click', '.delete-chamado', function () {
            const userId = $(this).data('id');

            if (confirm('Tem certeza que deseja excluir este chamado?')) {
                $.ajax({
                    url: "{{ url('api.chamados.delete') }}",
                    method: "DELETE",
                    success: function (response) {
                        table.ajax.reload();
                        showAlert('Chamado excluído com sucesso', 'success');
                    },
                    error: function (xhr, status, error) {
                        console.log('Delete error:', xhr, status, error);
                        showAlert('Erro ao excluir o chamado', 'danger');
                    }
                });
            }
        });

        $('#createChamadoModal').on('hidden.bs.modal', function () {
            resetModal('#createChamadoForm', '#modalErrors');
        });

        $('#editChamadoModal').on('hidden.bs.modal', function () {
            resetModal('#editChamadoForm', '#editModalErrors');
        });
    });


    function showAlert(message, type) {
        if (typeof toastr !== 'undefined') {
            toastr[type](message);
        } else {
            alert(message);
        }
    }

    function resetModal(formSelector, errorDivSelector) {
        $(formSelector)[0].reset();
        $(errorDivSelector).addClass('d-none').html('');
        $(formSelector + ' button[type="submit"]').prop('disabled', false);
        $(formSelector + ' .spinner-border').addClass('d-none');
    }

    function sleep(ms) {
        return new Promise(resolve => setTimeout(resolve, ms));
    }

    async function demo() {
        for (let i = 0; i < 10; i++) {
            console.log(`Waiting ${i} seconds...`);
            await sleep(i * 1000);
        }
        console.log('Done');
    }
</script>