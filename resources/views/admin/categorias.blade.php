@extends('layouts.admin')


@section('css')
    @include('admin.css.chamado-css')
@endsection

@section('title', 'Categorias')

@section(section: 'content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Categorias</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Categorias</li>
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
            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createCategoriaModal"
                id="createCategoriaBtn">
                <i class="fas fa-plus"></i> Criar Categoria
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
                    <th>Nome</th>
                    <th>Criado em</th>
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
            <button class="fab-action create" data-tooltip="Nova Categoria" data-bs-toggle="modal"
                data-bs-target="#createCategoriaModal" id="createCategoriaBtn">
                <i class="fas fa-plus"></i>
            </button>
            <button class="fab-action sync btn-success" data-tooltip="Recarregar" onclick="refreshTable()">
                <i class="fas fa-sync"></i>
            </button>
        </div>
        <button class="fab-main pulse" id="fabMain">
            <i class="fas fa-plus"></i>
        </button>
    </div>
</div>
@include('admin.modals.categorias-modals.create-categoria')
@include('admin.modals.categorias-modals.edit-categoria')
@stop

@section('js')
@include('admin.scripts.categorias-scripts.categorias-scripts')
@stop

@vite('resources/js/dataTables.js')