@extends('layouts.admin')

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
        .jsgrid {
            border: 1px solid #dee2e6;
            font-family: 'Source Sans Pro', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue'
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
            $ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')

                }
            })
        })

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
                        updateChamadoCount(data.length);
                    }).fail(function (xhr, status, error) {
                        console.error("Erro ao carregar dados:", error);
                        showAlert('Erro ao carregar os dados dos chamados', 'danger');
                    });
                },

                insertItem: function (item) {
                    return $.ajax({
                        url: "{{ route('api.chamados.post') }}",
                        method: "POST",
                        data: item,
                        dataType: "json"
                    }).done(function (response) {
                        if (response.success) {
                            showAlert('Chamado criado com sucesso', 'success');
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
                { name: 'id', title: "ID", type: "number", width: 50, inserting: false, editing: false },
                { name: 'titulo', title: "Título", type: "text", width: 150, validate: "required" },
                { name: 'descricao', title: "Descrição", type: "text", width: 150, validate: "required" },
                {
                    name: 'departamento', title: "Departamento", type: "select", items: [
                        { value: "SUPORTE", text: "SUPORTE" },
                        { value: "RH", text: "RH" },
                        { value: "MARKETING", text: "MARKETING" },
                        { value: "FINANCEIRO", text: "FINANCEIRO" },
                        { value: "VENDAS", text: "VENDAS" },
                        { value: "DESENVOLVIMENTO", text: "DESENVOLVIMENTO" }
                    ], width: 120, validate: "required"
                },
                {
                    name: 'prioridade', title: "Prioridade", type: "select", items: [
                        { value: "BAIXA", text: "BAIXA" },
                        { value: "MEDIA", text: "MEDIA" },
                        { value: "ALTA", text: "ALTA" },
                        { value: "CRITICA", text: "CRITICA" }
                    ], width: 120, validate: "required"
                },
                {
                    name: 'status', title: "Status", type: "select", items: [
                        { value: "ABERTO", text: "ABERTO" },
                        { value: "FECHADO", text: "FECHADO" },
                        { value: "EM ANDAMENTO", text: "EM ANDAMENTO" }
                    ], width: 120, validate: "required"
                },
                {
                    name: "data_abertura", title: "Data Abertura", type: "text", width: 120, inserting: false, editing: false,
                    itemTemplate: function (value) {
                        return value ? new Date(value).toLocaleDateString() : '';
                    }
                },
                { type: "control", width: 50 }
            ]
        });
    </script>

    @stop