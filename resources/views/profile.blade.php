@extends('layouts.app')

@section('title', 'Perfil - Sistema de Vendas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-user-circle me-2"></i>
                    Meu Perfil
                </h4>
            </div>
            <div class="card-body">
                <!-- Profile Info -->
                <div class="row mb-4">
                    <div class="col-md-4 text-center">
                        <div class="profile-avatar mb-3">
                            <div class="avatar-large">
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $user->name }}</h5>
                        <p class="text-muted mb-0">{{ $user->email }}</p>
                        <small class="text-muted">
                            Membro desde {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y') }}
                        </small>
                    </div>
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Data de Cadastro</h6>
                                        <p>{{ \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-card">
                                    <div class="info-icon">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                    <div class="info-content">
                                        <h6>Último Acesso</h6>
                                        <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Edit Profile Form -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-edit me-2"></i>
                            Editar Perfil
                        </h5>
                        
                        <form action="{{ route('profile.update') }}" method="POST" id="profileForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">
                                            <i class="fas fa-user me-1"></i>Nome Completo
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('name') is-invalid @enderror" 
                                               id="name" 
                                               name="name" 
                                               value="{{ old('name', $user->name) }}" 
                                               required>
                                        @error('name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>E-mail
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email', $user->email) }}" 
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-2"></i>
                                    Salvar Alterações
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Change Password -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-lock me-2"></i>
                            Alterar Senha
                        </h5>
                        
                        <form action="{{ route('profile.password') }}" method="POST" id="passwordForm">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">
                                            <i class="fas fa-key me-1"></i>Senha Atual
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('current_password') is-invalid @enderror" 
                                               id="current_password" 
                                               name="current_password" 
                                               required>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">
                                            <i class="fas fa-lock me-1"></i>Nova Senha
                                        </label>
                                        <input type="password" 
                                               class="form-control @error('new_password') is-invalid @enderror" 
                                               id="new_password" 
                                               name="new_password" 
                                               required>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label for="new_password_confirmation" class="form-label">
                                            <i class="fas fa-lock me-1"></i>Confirmar Nova Senha
                                        </label>
                                        <input type="password" 
                                               class="form-control" 
                                               id="new_password_confirmation" 
                                               name="new_password_confirmation" 
                                               required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end">
                                <button type="submit" class="btn btn-warning">
                                    <i class="fas fa-key me-2"></i>
                                    Alterar Senha
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <hr class="my-4">

                <!-- Danger Zone -->
                <div class="row">
                    <div class="col-12">
                        <h5 class="mb-3 text-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Zona de Perigo
                        </h5>
                        
                        <div class="alert alert-danger">
                            <h6 class="alert-heading">Excluir Conta</h6>
                            <p class="mb-3">
                                Esta ação é irreversível. Todos os seus dados serão permanentemente excluídos.
                            </p>
                            <button type="button" 
                                    class="btn btn-danger" 
                                    onclick="confirmDeleteAccount()">
                                <i class="fas fa-trash me-2"></i>
                                Excluir Minha Conta
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .profile-avatar {
        margin-bottom: 1.5rem;
    }
    
    .avatar-large {
        width: 120px;
        height: 120px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 2.5rem;
        font-weight: 700;
        margin: 0 auto;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    }
    
    .info-card {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: rgba(99, 102, 241, 0.05);
        border-radius: 12px;
        margin-bottom: 1rem;
    }
    
    .info-icon {
        width: 50px;
        height: 50px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        margin-right: 1rem;
    }
    
    .info-content h6 {
        margin: 0;
        font-size: 0.875rem;
        color: #6b7280;
        font-weight: 600;
    }
    
    .info-content p {
        margin: 0;
        font-weight: 600;
        color: var(--dark-color);
    }
    
    .alert-danger {
        border: none;
        border-radius: 12px;
        background: rgba(239, 68, 68, 0.1);
        color: #dc2626;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#profileForm').on('submit', function() {
        showLoading();
    });
    
    $('#passwordForm').on('submit', function() {
        showLoading();
    });
    
    // Password confirmation check
    $('#new_password_confirmation').on('input', function() {
        const password = $('#new_password').val();
        const confirmation = $(this).val();
        
        if (confirmation.length > 0) {
            if (password === confirmation) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else {
                $(this).removeClass('is-valid').addClass('is-invalid');
            }
        } else {
            $(this).removeClass('is-valid is-invalid');
        }
    });
});

function confirmDeleteAccount() {
    if (confirm('Tem certeza que deseja excluir sua conta? Esta ação é irreversível e todos os seus dados serão perdidos.')) {
        if (confirm('Esta é sua última chance. Tem certeza absoluta?')) {
            showLoading();
            // TODO: Implement account deletion
            alert('Funcionalidade de exclusão de conta será implementada em breve!');
            hideLoading();
        }
    }
}
</script>
@endsection 