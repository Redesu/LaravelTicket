@extends('layouts.admin')

@section('title', 'Configurações')

@section('content_header')
<div class="container-fluid">
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Configurações</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="#">Home</a></li>
                <li class="breadcrumb-item active">Configurações</li>
            </ol>
        </div>
    </div>
</div>
@stop

@section('content')
    <div class="container-fluid">
        <form id="updateUserForm" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="avatar">Avatar</label>
                        <div class="mb-2">
                            <img id="avatarPreview" src="{{ $user->avatar_url ?? '' }}" alt="{{ $user->name }}"
                                class="avatar"
                                style="width: 150px; height:150px; border-radius: 50%; object-fit: cover; {{ !$user->avatar_url ? 'display: none;' : '' }}">
                            <div id="avatarPlaceholder"
                                class="{{ $user->avatar_url ? 'd-none' : 'd-flex align-items-center justify-content-center' }}"
                                style="width: 150px; height: 150px; border-radius: 50%; background-color: #f8f9fa; border: 2px dashed #dee2e6;">
                                <i class="fas fa-user text-muted" style="font-size: 2rem;"></i>
                            </div>
                        </div>
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        <small class="form-text text-muted">Selecione uma imagem para ver a prévia</small>
                        @error('avatar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <input type="hidden" id="updateUserId" name="id" value="{{ $user->id }}">
                        <label for="name">Nome</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                            value="{{ old('name', $user->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="email">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                            name="email" value="{{ old('email', $user->email) }}" required>
                        @error('email')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password">Nova Senha</label>
                        <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                            name="password">
                        <small class="form-text text-muted">Deixe em branco se não quiser alterar a senha</small>
                        @error('password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="password_confirmation">Confirmar Nova Senha</label>
                        <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary" id="updateSubmitBtn">
                            <span class="spinner-border spinner-border-sm d-none" id="updateSpinner"></span>
                            <i class="fas fa-save"></i> Salvar
                        </button>
                        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection

@section('js')
    <script>
        window.routes = {
            updateUser: "{{ route('api.users.put', $user->id) }}"
        }
    </script>
@endsection

@vite('resources/js/settings.js')