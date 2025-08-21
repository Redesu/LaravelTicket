@push('css')
    <!-- Toastr CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
@endpush
@push('js')
<script src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>


<!-- DataTables -->
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.2/js/responsive.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.bootstrap4.js"></script>
<script src="https://cdn.datatables.net/buttons/3.0.2/js/buttons.html5.js"></script>

<!-- For Excel export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<!-- For PDF export -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script>
    $(document).ready(function () {

        const currentUserId = {{ auth()->user()->id ?? 'null'}};

        var table = $('#dataTable').DataTable({
            responsive: true,
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('api.chamados.data-tables') }}",
                data: function (d) {
                    console.log('DataTable data function called');
                    console.log('Status element exists:', $('#status').length > 0);
                    console.log('Status element:', $('#status'));
                    console.log('Status value:', $('#status').val());

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
                    showAlert(`AJAX Error: ${xhr}, ${error}, ${thrown}`, 'error');
                    showAlert(`Response Text: ${xhr.responseText}`, 'error');
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
                            actions += `<button class="btn btn-primary btn-sm edit-btn" data-bs-toggle="modal" data-bs-target="#editChamadosModal"><i class="fas fa-edit"></i> Editar</button> `;
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
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json',
                processing: '<i class="fas fa-spinner fa-spin fa-2x"></i><br>Carregando dados...'
            },
            initComplete: function () {
                showAlert('Chamados carregados com sucesso', 'success');
            },
        });

        $('#dataTable tbody').on('click', 'tr', function (e) {
            if ($(e.target).closest('button').length) {
                return;
            }

            var data = table.row(this).data();
            if (data) {
                window.location.href = `{{ route('admin.chamados.show', ':id') }}`.replace(':id', data.id);
            }
        });

        $('#dataTable').on('click', '.delete-btn', function () {
            var id = $(this).data('id');
            if (confirm('Tem certeza que deseja excluir este chamado?')) {
                $.ajax({
                    url: "{{ route('api.chamados.delete') }}",
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
                        showAlert(`AJAX Error: ${xhr}, ${error}, ${status}`, 'error');
                        showAlert('Erro ao excluir o chamado', 'error');
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
            $('#editChamadosModal').modal('show');
            $('#editChamadosForm').off('submit'); // Remove previous submit handler
        });

        $('#refreshBtn').on('click', function () {
            table.ajax.reload();
            showAlert('Chamados recarregados com sucesso', 'success');
        });

        $('#clearFilters').on('click', function () {
            $('#filtrarChamadosForm')[0].reset();

            $('#filtrarChamadosForm select').each(function () {
                $(this).val('');
            });

            $('#filtrarChamadosForm input[type="date"]').each(function () {
                $(this).val('');
            });

            table.ajax.reload();

            $('#filtrarChamadosModal').modal('hide');
            showAlert('Filtros limpos com sucesso', 'success');
        })

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
                data: $(this).serialize(),
                success: function (response) {
                    table.ajax.reload();
                    $('#createChamadoModal').modal('hide');
                    showAlert('Chamado criado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert(`AJAX Error: ${xhr}, ${error}, ${status}`, 'error');
                    if (xhr.responseJSON && xhr.responseJSON.errors) {
                        let errorMsg = '<ul>';
                        $.each(xhr.responseJSON.errors, function (key, value) {
                            errorMsg += '<li>' + value + '</li>';
                        });
                        errorMsg += '</ul>';
                        errorDiv.html(errorMsg);
                        errorDiv.removeClass('d-none');
                    }
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
            const errorDiv = $('#editChamadosModalErrors');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            errorDiv.addClass('d-none');

            status.prop('disabled', false);
            const formData = $(this).serialize();
            status.prop('disabled', true);

            let updateUrl = "{{ route('api.chamados.put', ':id') }}";
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
                    $('#editChamadosModal').modal('hide');
                    showAlert('Chamado atualizado com sucesso', 'success');
                },
                error: function (xhr, status, error) {
                    showAlert(`AJAX Error: ${xhr}, ${error}, ${status}`, 'error');
                    errorDiv.html(xhr.responseJSON.errors);
                    errorDiv.removeClass('d-none');
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
            const errorDiv = $('#filtrarModalErrors');

            submitBtn.prop('disabled', true);
            spinner.removeClass('d-none');
            errorDiv.addClass('d-none');

            table.ajax.reload(function (json) {
                $('#filtrarChamadosModal').modal('hide');

                showAlert('Filtros aplicados com sucesso', 'success');

                resetModal('#filtrarChamadosForm', '#filtrarModalErrors');
            })
        });



        $('#createChamadoModal').on('hidden.bs.modal', function () {
            resetModal('#createChamadoForm', '#modalErrors');
        });

        $('#editChamadosModal').on('hidden.bs.modal', function () {
            resetModal('#editChamadosForm', '#editModalChamadosErrors');
        });

        $('#filtrarChamadosModal').on('hidden.bs.modal', function () {
            resetModal('#filtrarChamadosForm', '#filtrarModalErrors');
        });
    });


    function showAlert(message, type) {
        if (typeof toastr !== 'undefined') {
            const validTypes = ['success', 'error', 'warning', 'info'];
            const toastrType = validTypes.includes(type) ? type : 'info';

            toastr[toastrType](message);
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

</script>