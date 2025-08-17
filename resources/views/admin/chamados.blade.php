@extends('layouts.admin')


@section('css')
    <!-- Custom CSS -->
    <style>
        #dataTable tbody tr {
            cursor: pointer;
        }

        #dataTable tbody tr:hover {
            background-color: #f8f9fa !important;
            transform: translateY(-1px);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        #dataTable tbody tr button {
            cursor: pointer;
            position: relative;
            z-index: 1;
        }
    </style>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createChamadoModal"
                id="createTicketBtn">
                <i class="fas fa-plus"></i> Criar Chamado
            </button>
            <button type="button" class="btn btn-success" id="refreshBtn">
                <i class="fas fa-sync"></i> Recarregar
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
                    <th>Usuário</TH>
                    <th>Data Abertura</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data will be loaded via AJAX -->
            </tbody>
        </table>


    </div>
    @include('admin.modals.create-chamado')
    @include('admin.modals.edit-chamados')

    @stop

    @section('js')

    @include('admin.scripts.chamados-scripts')

    @stop