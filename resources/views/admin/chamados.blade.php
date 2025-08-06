@extends('layouts.admin')


@section('css')
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap4.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap4.min.css">
@endsection

@section('title', 'Chamados')

@section(section: 'content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Chamados</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Chamados</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid">

    <!--- Action buttons --->

    <div class="row mb-3">
        <div class="col-12">
            <button type="button" class="btn btn-success" id="refreshBtn">
                <i class="fas fa-sync"></i> Recarregar
            </button>
            <button type="button" class="btn btn-warning" id="exportBtn">
                <i class="fas fa-download"></i> Exportar
            </button>
        </div>
    </div>

    <!--- Grid --->

    <div class="table-responsive">
        <table id="dataTable" class="table table-bordered table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Titulo</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Prioridade</th>
                    <th>Categoria</th>
                    <th>Departamento</th>
                    <th>Data Abertura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>
    </div>

    @stop

    @section('js')
    <!-- DataTables JS - Move to scripts section -->
    <script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap4.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>

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
                        url: `/api/chamados/${id}`,
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
        });

        function showAlert(message, type) {
            if (typeof toastr !== 'undefined') {
                toastr[type](message);
            } else {
                alert(message);
            }
        }
    </script>

    @stop