@extends('layouts.admin')


@section('css')
    @include('admin.css.chamado-css')
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
            <button type="button" class="btn btn-info" data-bs-toggle="modal" data-bs-target="#filtrarChamadosModal">
                <i class="fas fa-filter"></i> Filtrar
            </button>
            <button type="button" class="btn btn-success" onclick="refreshTable()">
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

    <div class="fab-backdrop" id="fabBackdrop"></div>
    <div class="fab-container" id="fabContainer">
        <div class="fab-actions">
            <button class="fab-action create" data-tooltip="Novo Chamado" data-bs-toggle="modal"
                data-bs-target="#createChamadoModal" id="createTicketBtn">
                <i class="fas fa-plus"></i>
            </button>
            <button class="fab-action filter solucao-btn" data-tooltip="Filtrar" data-bs-toggle="modal"
                data-bs-target="#filtrarChamadosModal">
                <i class="fas fa-filter"></i>
            </button>
            <button class="fab-action sync btn-success" data-tooltip="Recarregar" onclick="refreshTable()">
                <i class="fas fa-sync"></i>
            </button>
        </div>
        <button class="fab-main pulse" id="fabMain">
            <i class="fas fa-plus"></i>
        </button>
    </div>
    @include('admin.modals.chamados-modals.create-chamado')
    @include('admin.modals.chamados-modals.edit-chamados')
    @include('admin.modals.chamados-modals.filtrar-chamados')

    @stop

    @section('js')

    <script>
        window.routes = {
            chamadosDataTable: '{{ route('api.chamados.data-tables') }}',
            chamadosShow: '{{ route('admin.chamados.show', ':id') }}',
            chamadosDelete: '{{ route('api.chamados.delete') }}',
            chamadosPost: '{{ route('api.chamados.post') }}',
            chamadosPut: '{{ route('api.chamados.put', ':id') }}',
        }

        window.currentUserId = {{ auth()->user()->id ?? 'null' }}
    </script>

    @stop

    @vite('resources/js/datatables.js')
    @vite('resources/js/chamados.js')