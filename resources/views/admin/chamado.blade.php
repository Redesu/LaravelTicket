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
                                <div class="col-8">
                                    <div class="form-group file-upload-component">
                                        <label>Anexo (Opcional - Múltiplos arquivos permitidos)</label>
                                        <div class="drop-zone">
                                            <span class="drop-zone-text">
                                                <i class="fas fa-cloud-upload-alt"></i>
                                                Arraste e solte os arquivos aqui ou clique para selecionar
                                            </span>
                                            <input type="file" class="d-none file-input" name="anexos[]" multiple
                                                accept=".jpg,.jpeg,.png,.pdf,.zip,.rar,.mp4">
                                        </div>
                                        <div class="invalid-feedback d-block file-feedback" style="display: none;">
                                        </div>
                                        <div class="selected-files-container mt-2" style="display: none;">
                                            <small class="text-muted">Arquivos selecionados:</small>
                                            <div class="selected-files-list"></div>
                                        </div>
                                    </div>
                                </div>
                                <button type="submit" class="btn btn-success" id="addCommentBtn">
                                    <span class="spinner-border spinner-border-sm d-none" id="comentarioSpinner"></span>
                                    <i class="fas fa-comment"></i> Adicionar Comentário
                                </button>
                                <button type="button" class="btn btn-secondary cancel-comment-btn">
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

                                {{-- Display Anexos for this comment --}}
                                @if($comentario->anexos && $comentario->anexos->count() > 0)
                                    <div class="mt-3">
                                        <h6><i class="fas fa-paperclip"></i> Arquivos anexados:</h6>
                                        <div class="row">
                                            @foreach($comentario->anexos as $anexo)
                                                <div class="col-md-6 col-lg-4 mb-2">
                                                    <div class="card card-outline">
                                                        <div class="card-body p-2">
                                                            <div class="d-flex align-items-center">
                                                                <i class="fas fa-file file-icon"
                                                                    data-file-type="{{ strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION)) }}"></i>
                                                                <div class="flex-grow-1 ml-2">
                                                                    <small class="font-weight-bold d-block filename-wrapper"
                                                                        title="{{ $anexo->nome_original }}">{{ $anexo->nome_original }}</small>
                                                                    <small class="text-muted">
                                                                        <span class="file-size"
                                                                            data-size="{{ $anexo->tamanho }}"></span> •
                                                                        {{ $anexo->uploader->name ?? 'N/A' }} •
                                                                        {{ $anexo->created_at->format('d/m/Y H:i') }}
                                                                    </small>
                                                                </div>
                                                                <a href="{{ route('api.anexos.download', $anexo->id) }}"
                                                                    class="btn btn-sm btn-outline-primary ml-2" title="Download">
                                                                    <i class="fas fa-download"></i>
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
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
                    @if($chamado->anexos && $chamado->anexos->count() > 0)
                        <div class="mt-3">
                            <h6><i class="fas fa-paperclip"></i> Arquivos anexados ao chamado:</h6>
                            <div class="row">
                                @foreach($chamado->anexos as $anexo)
                                    <div class="col-md-6 col-lg-4 mb-2">
                                        <div class="card card-outline">
                                            <div class="card-body p-2">
                                                <div class="d-flex align-items-center">
                                                    <i class="fas fa-file file-icon"
                                                        data-file-type="{{ strtolower(pathinfo($anexo->nome_original, PATHINFO_EXTENSION)) }}"></i>
                                                    <div class="flex-grow-1 ml-2">
                                                        <small
                                                            class="font-weight-bold d-block">{{ $anexo->nome_original }}</small>
                                                        <small class="text-muted">
                                                            <span class="file-size" data-size="{{ $anexo->tamanho }}"></span> •
                                                            {{ $anexo->uploader->name ?? 'N/A' }} •
                                                            {{ $anexo->created_at->format('d/m/Y H:i') }}
                                                        </small>
                                                    </div>
                                                    <button class="btn btn-sm btn-outline-primary ml-2 download-btn"
                                                        data-anexo-id="{{ $anexo->id }}"
                                                        data-filename="{{ $anexo->nome_original }}" title="Download">
                                                        <i class="fas fa-download"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                    <small class="text-muted">
                        <i class="fas fa-calendar"></i> Criado em:
                        {{ $chamado->created_at ? $chamado->created_at->format('d/m/Y H:i') : 'N/A' }}
                    </small>
                </div>
            </div>

            <!-- Action buttons -->
            <div class="card">
                <div class="card-body">
                    <a class="btn btn-secondary voltar-btn" id="voltarBtn">
                        <span class="spinner-border spinner-border-sm d-none" id="voltarSpinner"></span>
                        <i class="fas fa-arrow-left"></i> Voltar
                    </a>
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
                                    <span>Criador por</span>:
                                </label>
                            </div>
                            <div class="col-8">
                                <span class="form-control-static">
                                    {{ $chamado->creator->name ?? 'N/A' }}
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
                                    {{ $chamado->created_at ? $chamado->created_at->format('d/m/Y H:i') : 'N/A' }}
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

<div class="fab-backdrop" id="fabBackdrop"></div>
<div class="fab-container" id="fabContainer">
    <div class="fab-actions">
        <a href="{{ route('admin.chamados') }}" class="fab-action back" data-tooltip="Voltar">
            <i class="fas fa-arrow-left"></i>
        </a>
        @if($chamado->status !== 'Finalizado')
            <button class="fab-action edit edit-btn" data-tooltip="Editar" data-bs-toggle="modal"
                data-bs-target="#editChamadosModal">
                <i class="fas fa-edit"></i>
            </button>
            <button class="fab-action resolve solucao-btn" data-tooltip="Resolver" data-bs-toggle="modal"
                data-bs-target="#solucaoChamadoModal">
                <i class="fas fa-check"></i>
            </button>
            <button class="fab-action comment adicionar-comentario-btn reply-btn" data-tooltip="Comentar">
                <i class="fas fa-comments"></i>
            </button>
        @endif
    </div>
    <button class="fab-main pulse" id="fabMain">
        <i class="fas fa-plus"></i>
    </button>
</div>

@include('admin.modals.chamados-modals.edit-chamados')
@include('admin.modals.chamado-modals.solucao-chamado')

@stop


@section('js')

    <script>
        window.routes = {
            addSolution: '{{ route('api.chamados.addSolution', $chamado->id) }}',
            addComment: '{{ route('api.chamados.addComentario', $chamado->id) }}',
            anexosDownload: '{{ route('api.anexos.download', ':anexoId') }}',
            chamadosPut: '{{ route('api.chamados.put', ':id') }}',
        }

        window.chamado = {
            status: '{{ $chamado->status }}',
            id: '{{ $chamado->id }}',
            titulo: '{{ $chamado->titulo }}',
            descricao: '{{ $chamado->descricao }}',
            status: '{{ $chamado->status }}',
            prioridade: '{{ $chamado->prioridade }}',
            departamento: '{{ $chamado->departamento->nome }}',
            categoria: '{{ $chamado->categoria->nome }}',
            usuario: '{{ $chamado->usuario->id }}'
        }
    </script>

@endsection

@vite('resources/js/chamado.js')