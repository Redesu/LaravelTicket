@extends('layouts.admin')

@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid-theme.min.css">

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
            <button type="button" class="btn btn-success" onclick="refreshGrid()">
                <i class="fas fa-sync"></i> Recarregar
            </button>
            <button type="button" class="btn btn-warning" onclick="exportData()">
                <i class="fas fa-download"></i> Exportar
            </button>
        </div>
    </div>

    <!--- Grid --->

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <div class="card-tools">
                        <span class="badge badge-primary" id="totalEmployees">0</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- jsGrid vai ser inicalizado aqui -->
                    <div id="jsGrid"></div>
                </div>
            </div>
        </div>
    </div>

    @stop

    @section('css')
    <style>
        .jsgrid-button {
            width: 16px;
            height: 16px;
            margin: 2px;
        }

        .jsgrid-insert-button:before {
            content: "➕";
        }

        .jsgrid-update-button:before {
            content: "✓";
        }

        .jsgrid-delete-button:before {
            content: "✖";
        }

        .jsgrid-edit-button:before {
            content: "✎";
        }

        .jsgrid-cancel-button:before {
            content: "✖";
        }

        /* Alternative using Font Awesome if available */
        .jsgrid-insert-button {
            background: none;
            border: none;
            color: #28a745;
            font-size: 14px;
        }

        .jsgrid-insert-button:after {
            font-family: "Font Awesome 5 Free";
            content: "\f067";
            /* fa-plus */
            font-weight: 900;
        }

        .jsgrid-edit-button:after {
            font-family: "Font Awesome 5 Free";
            content: "\f044";
            /* fa-edit */
            font-weight: 900;
        }

        .jsgrid-delete-button:after {
            font-family: "Font Awesome 5 Free";
            content: "\f1f8";
            /* fa-trash */
            font-weight: 900;
        }

        .jsgrid-update-button:after {
            font-family: "Font Awesome 5 Free";
            content: "\f00c";
            /* fa-check */
            font-weight: 900;
        }

        .jsgrid-cancel-button:after {
            font-family: "Font Awesome 5 Free";
            content: "\f00d";
            /* fa-times */
            font-weight: 900;
        }
    </style>
    @stop

    @section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/admin-lte/3.2.0/js/adminlte.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jsgrid/1.5.3/jsgrid.min.js"></script>


    <script>
        $(document).ready(function () {

            const currentUserId = {{ auth()->user()->id ?? 'null'}};

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }
            });

            $("#jsGrid").jsGrid({
                width: "100%",
                height: "500px",
                inserting: true,
                editing: true,
                sorting: true,
                paging: true,
                pageSize: 15,
                pageButtonCount: 5,
                deleteConfirm: "Tem certeza que deseja excluir?",

                controller: {
                    loadData: function (filter) {
                        return $.ajax({
                            url: "{{  route('api.chamados.get') }}",
                            method: "GET",
                            data: filter,
                            dataType: "json"
                        }).done(function (data) {
                            console.log("Resposta da API:", data);
                            updateChamadoCount(data.length);
                        }).fail(function (xhr, status, error) {
                            console.error("Erro ao carregar dados:", error);
                            showAlert('Erro ao carregar os dados dos chamados', 'danger');
                        });
                    },

                    insertItem: function (item) {
                        console.log("item: ", item);
                        return $.ajax({
                            url: "{{ route('api.chamados.post') }}",
                            method: "POST",
                            data: item,
                            dataType: "json"
                        }).done(function (response) {
                            if (response.success) {
                                showAlert('Chamado criado com sucesso', 'success');
                                refreshGrid();
                                return response.data;
                            }
                        }).fail(function (xhr, status, error) {
                            console.error("Erro ao criar chamado:", error);
                            showAlert('Erro ao criar o chamado', 'danger');
                        })
                    },

                    updateItem: function (item) {

                        return $.ajax({
                            url: "{{ route('api.chamados.put') }}",
                            method: "PUT",
                            data: item,
                            dataType: "json"
                        }).done(function (respone) {
                            if (respone.success) {
                                showAlert('Chamado atualizado com sucesso', 'success');
                                return respone.data;
                            }
                        }).fail(function (xhr, status, error) {
                            console.error("Erro ao atualizar chamado:", error);
                            showAlert('Erro ao atualizar o chamado', 'danger');
                        })
                    },
                    deleteItem: function (item) {
                        item.user_id = currentUserId; // Adiciona o ID do usuário ao item
                        return $.ajax({
                            url: "{{ route('api.chamados.delete') }}",
                            method: "DELETE",
                            data: item,
                            dataType: "json"
                        }).done(function (response) {
                            if (response.success) {
                                showAlert('Chamado excluído com sucesso', 'success');
                                return response.data;
                            }
                        }).fail(function (xhr, status, error) {
                            console.error("Erro ao excluir chamado:", error);
                            showAlert('Erro ao excluir o chamado', 'danger');
                        });
                    }
                },

                fields: [
                    { name: 'id', title: "ID", type: "number", width: 100, inserting: false, editing: false },
                    { name: 'user_id', type: "hidden", inserting: false, editing: false, visible: false },
                    { name: 'titulo', title: "Título", type: "text", width: 150, validate: "required" },
                    { name: 'descricao', title: "Descrição", type: "text", width: 400, validate: "required" },
                    {
                        name: 'status', title: "Status", type: "select", items: [
                            { Name: "ABERTO", Id: "Aberto" },
                            { Name: "EM ANDAMENTO", Id: "EM ANDAMENTO" },
                            { Name: "FECHADO", Id: "FECHADO" }
                        ], valueField: "Id", textField: "Name", width: 120, validate: "required"
                    },
                    {
                        name: 'prioridade', title: "Prioridade", type: "select", items: [
                            { Name: "BAIXA", Id: "BAIXA" },
                            { Name: "MEDIA", Id: "Média" },
                            { Name: "ALTA", Id: "ALTA" },
                            { Name: "CRITICA", Id: "CRITICA" }
                        ], valueField: "Id", textField: "Name", width: 120, validate: "required"
                    },
                    {
                        name: 'categoria_id', title: "Categoria", type: "select", items: [
                            { Name: "", Id: "" },
                            { Name: "SUPORTE", Id: 1 },
                            { Name: "DUVIDAS", Id: 2 },
                        ], valueField: "Id", textField: "Name", width: 120, validate: "required"
                    },
                    {
                        name: 'departamento_id', title: "Departamento", type: "select", items: [
                            { Name: "", Id: "" },
                            { Name: "SUPORTE", Id: 1 },
                            { Name: "DESENVOLVIMENTO", Id: 2 }
                        ], valueField: "Id", textField: "Name", width: 120, validate: "required"
                    },
                    {
                        name: "created_at", title: "Data Abertura", type: "text", width: 120, inserting: false, editing: false,
                        itemTemplate: function (value) {
                            return value ? new Date(value).toLocaleDateString() : '';
                        }
                    },
                    { type: "control", width: 50 }
                ]
            });
            $("#jsGrid").jsGrid("loadData");
        });

        function refreshGrid() {
            $("#jsGrid").jsGrid("loadData");
            showAlert('Lista de chamados atualizada', 'info');
        }

        function updateChamadoCount(count) {

            $("#totalChamados").text(count + ' Chamados');
        }

        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    ${message}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                `;
            $('body').append(alertHtml);
            setTimeout(function () {
                $('.alert').alert('close');
            }, 3000);
        }

        function exportData() {
            $.ajax({
                url: "{{ route('api.chamados.get') }}",
                method: "GET",
                dataType: "json",
                success: function (data) {
                    let csv = 'ID;Título;Descrição;Departamento;Prioridade;Data Abertura\n';
                    data.forEach(function (chamado) {
                        csv += `${chamado.id};${chamado.titulo};${chamado.descricao};${chamado.departamento};${chamado.prioridade};${chamado.data_abertura}\n`;
                    });

                    const blob = new Blob([csv], { type: 'text/csv;charset=utf-8;' });
                    const url = URL.createObjectURL(blob);
                    const a = document.createElement('a');
                    a.href = url;
                    a.download = 'chamados.csv';
                    a.click();
                    URL.revokeObjectURL(url);
                    showAlert('Dados exportados com sucesso', 'success');
                },
                error: function () {
                    showAlert('Erro ao exportar os dados', 'danger');
                }
            })
        }

    </script>

    @stop