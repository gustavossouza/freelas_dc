@extends('layouts.app')

@section('title', 'Login - Sistema de Vendas')

@section('styles')
<style>
    .login-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .login-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
        padding: 3rem;
        width: 100%;
        max-width: 450px;
        position: relative;
        overflow: hidden;
    }
    
    .login-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }
    
    .login-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .login-logo {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
        box-shadow: 0 10px 30px rgba(99, 102, 241, 0.3);
    }
    
    .login-logo i {
        font-size: 2rem;
        color: white;
    }
    
    .login-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
        color: #6b7280;
        font-size: 1rem;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
        position: relative;
    }
    
    .form-label {
        font-weight: 600;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
        display: block;
    }
    
    .form-control {
        width: 100%;
        padding: 1rem 1.5rem;
        border: 2px solid #e5e7eb;
        border-radius: 12px;
        font-size: 1rem;
        transition: all 0.3s ease;
        background: rgba(255, 255, 255, 0.8);
    }
    
    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.1);
        transform: translateY(-2px);
    }
    
    .input-icon {
        position: absolute;
        right: 1rem;
        top: 50%;
        transform: translateY(-50%);
        color: #9ca3af;
        transition: all 0.3s ease;
    }
    
    .form-control:focus + .input-icon {
        color: var(--primary-color);
    }
    
    .btn-login {
        width: 100%;
        padding: 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border: none;
        border-radius: 12px;
        color: white;
        font-size: 1.1rem;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        position: relative;
        overflow: hidden;
    }
    
    .btn-login::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-login:hover::before {
        left: 100%;
    }
    
    .btn-login:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
    }
    
    .btn-login:active {
        transform: translateY(-1px);
    }
    
    .form-check {
        display: flex;
        align-items: center;
        margin: 1.5rem 0;
    }
    
    .form-check-input {
        margin-right: 0.75rem;
        width: 1.2rem;
        height: 1.2rem;
        border-radius: 6px;
        border: 2px solid #d1d5db;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .form-check-label {
        color: #6b7280;
        cursor: pointer;
        user-select: none;
    }
    
    .floating-shapes {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        overflow: hidden;
        z-index: -1;
    }
    
    .shape {
        position: absolute;
        background: linear-gradient(135deg, rgba(99, 102, 241, 0.1), rgba(139, 92, 246, 0.1));
        border-radius: 50%;
        animation: float 6s ease-in-out infinite;
    }
    
    .shape:nth-child(1) {
        width: 100px;
        height: 100px;
        top: 20%;
        left: 10%;
        animation-delay: 0s;
    }
    
    .shape:nth-child(2) {
        width: 150px;
        height: 150px;
        top: 60%;
        right: 10%;
        animation-delay: 2s;
    }
    
    .shape:nth-child(3) {
        width: 80px;
        height: 80px;
        bottom: 20%;
        left: 20%;
        animation-delay: 4s;
    }
    
    @keyframes float {
        0%, 100% {
            transform: translateY(0px) rotate(0deg);
        }
        50% {
            transform: translateY(-20px) rotate(180deg);
        }
    }
    
    .error-message {
        color: var(--danger-color);
        font-size: 0.875rem;
        margin-top: 0.5rem;
        display: flex;
        align-items: center;
    }
    
    .error-message i {
        margin-right: 0.5rem;
    }
</style>
@endsection

@section('content')
<div class="login-container">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="login-card fade-in">
        <div class="login-header">
            <div class="login-logo">
                <i class="fas fa-chart-line"></i>
            </div>
            <h1 class="login-title">Bem-vindo</h1>
            <p class="login-subtitle">Faça login para acessar o sistema</p>
        </div>
        
        <form method="POST" action="{{ route('login') }}" id="loginForm">
            @csrf
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-2"></i>E-mail
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="Digite seu e-mail"
                       required 
                       autofocus>
                <i class="fas fa-envelope input-icon"></i>
                @error('email')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="password" class="form-label">
                    <i class="fas fa-lock me-2"></i>Senha
                </label>
                <input type="password" 
                       id="password" 
                       name="password" 
                       class="form-control @error('password') is-invalid @enderror" 
                       placeholder="Digite sua senha"
                       required>
                <i class="fas fa-lock input-icon"></i>
                @error('password')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-check">
                <input type="checkbox" 
                       id="remember" 
                       name="remember" 
                       class="form-check-input"
                       {{ old('remember') ? 'checked' : '' }}>
                <label for="remember" class="form-check-label">
                    Lembrar de mim
                </label>
            </div>
            
            <button type="submit" class="btn-login">
                <i class="fas fa-sign-in-alt me-2"></i>
                Entrar
            </button>
        </form>
        
        <div class="text-center mt-3">
            <p class="mb-2">
                <a href="{{ route('register') }}" class="text-decoration-none" style="color: var(--primary-color);">
                    Não tem uma conta? Cadastre-se aqui
                </a>
            </p>
            @if (Route::has('password.request'))
                <p class="mb-0">
                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--primary-color);">
                        Esqueceu sua senha?
                    </a>
                </p>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#loginForm').on('submit', function() {
        showLoading();
    });
    
    // Input focus effects
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
    
    // Password visibility toggle
    $('#password').on('input', function() {
        if ($(this).val().length > 0) {
            $(this).next('.input-icon').removeClass('fa-lock').addClass('fa-eye');
        } else {
            $(this).next('.input-icon').removeClass('fa-eye').addClass('fa-lock');
        }
    });
    
    // Eye icon click to toggle password visibility
    $(document).on('click', '.fa-eye', function() {
        const input = $(this).prev('input');
        if (input.attr('type') === 'password') {
            input.attr('type', 'text');
            $(this).removeClass('fa-eye').addClass('fa-eye-slash');
        } else {
            input.attr('type', 'password');
            $(this).removeClass('fa-eye-slash').addClass('fa-eye');
        }
    });
    
    // Auto-hide error messages
    setTimeout(function() {
        $('.error-message').fadeOut('slow');
    }, 5000);
});
</script>
@endsection 