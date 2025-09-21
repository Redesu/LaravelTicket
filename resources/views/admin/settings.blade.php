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
                        @if($user->avatar)
                            <div class="mb-2">
                                <img src="{{ url("storage/{$user->avatar}") }}" alt="{{ $user->name }}" class="avatar"
                                    style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                            </div>
                        @endif
                        <input type="file" class="form-control" id="avatar" name="avatar" accept="image/*">
                        @error('avatar')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
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
                        <button type="submit" class="btn btn-primary">
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
    </script>
@endsection