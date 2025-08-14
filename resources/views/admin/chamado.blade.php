@extends('layouts.admin')

@section('title', 'Chamados')

@section(section: 'content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1>Detalhes do chamado: #{{ $chamado->id }} - {{ $chamado->titulo }} </h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active"><a href="{{ route('admin.chamados') }}">Chamados</a></li>
                <li class="breadcrumb-item active">Chamado #{{ $chamado->id }}</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')

<div class="container-fluid">
    <div class="card card-primary card-outline">
        <div class="card-body">
            <p>{{ $chamado->descricao }}</p>
        </div><!-- /.card-body -->
    </div>
</div><!-- /.container-fluid -->

<div class="card">
    <p class="card-header bg-purple">Chamado</p>
    <div class="card-body pb-0 pt-1">
        <div class="form-group">
            <div class="row">
                <div class="col-md-4 text-right"><label class="control-label text-right"><span
                            translate="">Prioridade</span>:</label></div>
                <div class="col-md-8">
                    <p class="form-control-static">
                        <span id="priority-badge" class="badge badge-secondary">
                            {{ $chamado->prioridade }}
                        </span>
                    </p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-right"><label class="control-label text-right"><span
                            translate="">Responsável</span>:</label></div>
                <div class="col-md-8">
                    {{ $chamado->usuario->name ?? 'N/A' }}
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-right"><label class="control-label text-right"><span
                            translate="">Departamento</span>:</label></div>
                <div class="col-md-8">
                    <p class="form-control-static ng-binding">{{ $chamado->departamento->nome }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-right"><label class="control-label text-right"><span
                            translate="">Categoria</span>:</label></div>
                <div class="col-md-8">
                    <p class="form-control-static ng-binding">{{ $chamado->categoria->nome }}</p>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 text-right"><label class="control-label text-right"><span translate="">Criado
                            em</span>:</label></div>
                <div class="col-md-8">
                    <p class="form-control-static ng-binding">{{ $chamado->data_abertura ?? 'N/A' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

@stop


@section('js')

    <script>
        console.log(`{{ $chamado }}`);
    </script>

    @include('admin.scripts.chamado-scripts')

@endsection