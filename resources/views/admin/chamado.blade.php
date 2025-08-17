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
    <div class="row">

        <!-- Main content - Description Card (Center/Left) -->
        <div class="col-md-8">

            <!-- Comments Section (Above Description) -->
            <div id="comments-section">
                <div class="card card-success card-outline" id="main-comment-form" style="display: none;">
                    <div class="card-body">
                        <form id="add-comment-form">
                            @csrf
                            <input type="hidden" name="parent_id" value="">
                            <div class="form-group">
                                <textarea class="form-control" name="descricao" rows="4"
                                    placeholder="Digite seu comentário..."></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" id="addCommentBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="comentarioSpinner"></span>
                                    <i class="fas fa-comment"></i> Enviar Comentário
                                </button>
                                <button type="button" class="btn btn-secondary" id="cancel-main-comment-form">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Add Comment Form -->
                <div class="card card-success card-outline collapsed-card collapsed" id="add-comment-card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-plus"></i>
                            Adicionar Comentário
                        </h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                <i class="fas fa-plus"></i>
                            </button>
                        </div>
                    </div>
                    <div class="card-body" style="display: none;">
                        <form id="add-comment-form">
                            @csrf
                            <div class="form-group">
                                <textarea class="form-control" id="comment-text" rows="4"
                                    placeholder="Digite seu comentário..."></textarea>
                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-success" id="addCommentBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="comentarioSpinner"></span>
                                    <i class="fas fa-comment"></i> Adicionar Comentário
                                </button>
                                <button type="button" class="btn btn-secondary" onclick="cancelComment()">
                                    <i class="fas fa-times"></i> Cancelar
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                @if($chamado->comentarios && $chamado->comentarios->count() > 0)
                    @foreach($chamado->comentarios->sortByDesc('created_at') as $comentario)
                        <div class="card card-widget @if($comentario->tipo == 'solution') card-success @endif"
                            id="comment-{{$comentario->id}}">
                            <div class="card-header">
                                <div class="user-block">
                                    <div class="d-flex align-items-center me-1">
                                        @if($comentario->usuario->avatar)
                                            <img class="img-circle" src="{{ $comentario->usuario->avatar }}" alt="User Image">
                                        @else
                                            <svg class="img-circle" width="40" height="40"
                                                data-jdenticon-value="{{ $comentario->usuario->name }}"></svg>
                                        @endif

                                        <span class="username ms-1">
                                            {{ $comentario->usuario->name }}
                                            @if($comentario->tipo == 'edit')
                                                <span class="badge badge-info ml-1">EDITADO</span>
                                            @elseif($comentario->tipo == 'solution')
                                                <span class="badge badge-success ml-1">SOLUÇÃO</span>
                                            @endif
                                        </span>
                                    </div>
                                    <span class="description">{{ $comentario->created_at->format('d/m/Y H:i') }}</span>
                                </div>
                                <div class="card-tools">
                                    <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                        <i class="fas fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="card-body">
                                @if($comentario->tipo == 'edit')
                                    <div class="callout callout-info">
                                        <h5><i class="fas fa-edit"></i> Chamado editado</h5>
                                        <p>{{ $comentario->descricao }}</p>
                                        @if($comentario->changes)
                                            <small class="text-muted">
                                                <strong>Alterações:</strong>
                                                @foreach(json_decode($comentario->changes, true) as $field => $change)
                                                    <br>• {{ ucfirst($field) }}: "{{ $change['old'] }}" → "{{ $change['new'] }}"
                                                @endforeach
                                            </small>
                                        @endif
                                    </div>
                                @elseif($comentario->tipo == 'solution')
                                    <div class="callout callout-success">
                                        <h5><i class="fas fa-check"></i> Solução</h5>
                                        <p>{{ $comentario->descricao }}</p>
                                    </div>
                                @else
                                    <p>{{ $comentario->descricao }}</p>
                                @endif
                            </div>
                            <div class="card-footer">
                                <button type="button" class="btn btn-default btn-sm reply-btn"
                                    data-comment-id="{{$comentario->id}}"><i class="fas fa-reply"></i> Responder</button>
                            </div>
                        </div>
                    @endforeach
                @endif
            </div>


            <!-- Original Description -->
            <div class="card card-primary card-outline">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-ticket-alt"></i>
                        Descrição Original do Chamado
                    </h3>
                </div>
                <div class="card-body">
                    <p class="lead">{{ $chamado->descricao }}</p>
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> Criado em:
                    </small>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="card">
                <div class="card-body">
                    <a href="{{ route('admin.chamados') }}" class="btn btn-secondary voltar-btn" id="voltarBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="voltarSpinner"></span>
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
                    @if($chamado->status !== 'Finalizado')
                        <button type="button" class="btn btn-primary ml-2 edit-btn" data-bs-toggle="modal"
                            data-bs-target="#editChamadosModal">
                            <i class="fas fa-edit"></i> Editar
                        </button>
                        <button type="button" class="btn btn-success ml-2 solucao-btn" data-bs-toggle="modal"
                            data-bs-target="#solucaoChamadoModal">
                            <i class="fas fa-check"></i> Resolver
                        </button>
                        <button type="button" class="btn btn-warning ml-2 adicionar-comentario-btn"
                            onclick="showAddComment()">
                            <i class="fas fa-comments"></i> Adicionar Comentário
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar - Chamado Information (Right) -->
        <div class="col-md-4">
            <div class="card card-info card-outline">
                <div class="card-header bg-info">
                    <h3 class="card-title text-white">
                        <i class="fas fa-info-circle"></i>
                        Informações do Chamado
                    </h3>
                </div>
                <div class="card-body pb-0 pt-3">

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Prioridade</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span id="priority-badge" class="badge badge-warning">
                                    {{ $chamado->prioridade }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Responsável</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span class="form-control-static">
                                    {{ $chamado->usuario->name ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Departamento</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span class="form-control-static">
                                    {{ $chamado->departamento->nome }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Categoria</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span class="form-control-static">
                                    {{ $chamado->categoria->nome }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Criado em</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span class="form-control-static">
                                    {{ $chamado->data_abertura ?? 'N/A' }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <div class="form-group mb-3">
                        <div class="row">
                            <div class="col-4 text-right">
                                <label class="control-label font-weight-bold">
                                    <span>Status</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span id="status-badge" class="badge badge-success">
                                    {{ $chamado->status }}
                                </span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </div>
</div>

@include('admin.modals.edit-chamados')
@include('admin.modals.solucao-chamado')

@stop


@section('js')

    @include('admin.scripts.chamado-scripts')

@endsection