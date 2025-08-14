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

    <!-- Main content card -->
    <div class="card card-primary card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-ticket-alt"></i>
                Descrição do Chamado
            </h3>
        </div>
        <div class="card-body">
            <p class="lead">{{ $chamado->descricao }}</p>
        </div>
    </div>

    <!-- Chamado details card -->
    <div class="card card-info card-outline">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-info-circle"></i>
                Informações do Chamado
            </h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Prioridade:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <span id="priority-badge" class="badge badge-warning">
                                    {{ $chamado->prioridade }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Responsável:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <i class="fas fa-user text-muted mr-1"></i>
                                {{ $chamado->usuario->name ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Departamento:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <i class="fas fa-building text-muted mr-1"></i>
                                {{ $chamado->departamento->nome }}
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Categoria:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <i class="fas fa-tags text-muted mr-1"></i>
                                {{ $chamado->categoria->nome }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Criado em:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <i class="fas fa-calendar text-muted mr-1"></i>
                                {{ $chamado->data_abertura ?? 'N/A' }}
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-sm-4 col-form-label text-right font-weight-bold">Status:</label>
                        <div class="col-sm-8">
                            <div class="form-control-plaintext">
                                <span class="badge badge-success">
                                    {{ $chamado->status ?? 'Aberto' }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Action buttons card -->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <a href="{{ route('admin.chamados') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    <button type="button" class="btn btn-primary ml-2">
                        <i class="fas fa-edit"></i> Editar
                    </button>
                    <button type="button" class="btn btn-success ml-2">
                        <i class="fas fa-check"></i> Resolver
                    </button>
                    <button type="button" class="btn btn-warning ml-2">
                        <i class="fas fa-comments"></i> Adicionar Comentário
                    </button>
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