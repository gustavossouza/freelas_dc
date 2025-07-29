@extends('layouts.app')

@section('title', 'Cadastro - Sistema de Vendas')

@section('styles')
<style>
    .register-container {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem;
    }
    
    .register-card {
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(20px);
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.15);
        padding: 3rem;
        width: 100%;
        max-width: 500px;
        position: relative;
        overflow: hidden;
    }
    
    .register-card::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    }
    
    .register-header {
        text-align: center;
        margin-bottom: 2.5rem;
    }
    
    .register-logo {
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
    
    .register-logo i {
        font-size: 2rem;
        color: white;
    }
    
    .register-title {
        font-size: 2rem;
        font-weight: 700;
        color: var(--dark-color);
        margin-bottom: 0.5rem;
    }
    
    .register-subtitle {
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
    
    .btn-register {
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
    
    .btn-register::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
    }
    
    .btn-register:hover::before {
        left: 100%;
    }
    
    .btn-register:hover {
        transform: translateY(-3px);
        box-shadow: 0 15px 35px rgba(99, 102, 241, 0.4);
    }
    
    .btn-register:active {
        transform: translateY(-1px);
    }
    
    .form-check {
        display: flex;
        align-items: flex-start;
        margin: 1.5rem 0;
    }
    
    .form-check-input {
        margin-right: 0.75rem;
        margin-top: 0.25rem;
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
        font-size: 0.9rem;
        line-height: 1.4;
    }
    
    .form-check-label a {
        color: var(--primary-color);
        text-decoration: none;
    }
    
    .form-check-label a:hover {
        text-decoration: underline;
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
    
    .password-strength {
        margin-top: 0.5rem;
        font-size: 0.875rem;
    }
    
    .strength-bar {
        height: 4px;
        border-radius: 2px;
        background: #e5e7eb;
        margin-top: 0.25rem;
        overflow: hidden;
    }
    
    .strength-fill {
        height: 100%;
        transition: all 0.3s ease;
        border-radius: 2px;
    }
    
    .strength-weak { background: #ef4444; width: 25%; }
    .strength-fair { background: #f59e0b; width: 50%; }
    .strength-good { background: #10b981; width: 75%; }
    .strength-strong { background: #059669; width: 100%; }
</style>
@endsection

@section('content')
<div class="register-container">
    <!-- Floating Shapes Background -->
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
        <div class="shape"></div>
    </div>
    
    <div class="register-card fade-in">
        <div class="register-header">
            <div class="register-logo">
                <i class="fas fa-user-plus"></i>
            </div>
            <h1 class="register-title">Criar Conta</h1>
            <p class="register-subtitle">Preencha os dados para começar</p>
        </div>
        
        <form method="POST" action="{{ route('register') }}" id="registerForm">
            @csrf
            
            <div class="form-group">
                <label for="name" class="form-label">
                    <i class="fas fa-user me-1"></i>Nome Completo *
                </label>
                <input type="text" 
                       id="name" 
                       name="name" 
                       class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" 
                       placeholder="Digite seu nome completo"
                       required 
                       autofocus>
                <i class="fas fa-user input-icon"></i>
                @error('name')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <div class="form-group">
                <label for="email" class="form-label">
                    <i class="fas fa-envelope me-1"></i>E-mail *
                </label>
                <input type="email" 
                       id="email" 
                       name="email" 
                       class="form-control @error('email') is-invalid @enderror" 
                       value="{{ old('email') }}" 
                       placeholder="Digite seu e-mail"
                       required>
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
                    <i class="fas fa-lock me-1"></i>Senha *
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
                <div class="password-strength" id="passwordStrength" style="display: none;">
                    <span id="strengthText">Força da senha: </span>
                    <div class="strength-bar">
                        <div class="strength-fill" id="strengthFill"></div>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <label for="password_confirmation" class="form-label">
                    <i class="fas fa-lock me-1"></i>Confirmar Senha *
                </label>
                <input type="password" 
                       id="password_confirmation" 
                       name="password_confirmation" 
                       class="form-control" 
                       placeholder="Confirme sua senha"
                       required>
                <i class="fas fa-lock input-icon"></i>
            </div>
            
            <div class="form-check">
                <input type="checkbox" 
                       id="terms" 
                       name="terms" 
                       class="form-check-input @error('terms') is-invalid @enderror"
                       {{ old('terms') ? 'checked' : '' }}
                       required>
                <label for="terms" class="form-check-label">
                    Eu aceito os <a href="#" onclick="showTerms()">Termos de Uso</a> e a <a href="#" onclick="showPrivacy()">Política de Privacidade</a> *
                </label>
                @error('terms')
                    <div class="error-message">
                        <i class="fas fa-exclamation-circle"></i>
                        {{ $message }}
                    </div>
                @enderror
            </div>
            
            <button type="submit" class="btn-register">
                <i class="fas fa-user-plus me-2"></i>
                Criar Conta
            </button>
        </form>
        
        <div class="text-center mt-4">
            <p class="mb-0">
                Já tem uma conta? 
                <a href="{{ route('login') }}" class="text-decoration-none" style="color: var(--primary-color);">
                    Faça login aqui
                </a>
            </p>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#registerForm').on('submit', function() {
        showLoading();
    });
    
    // Input focus effects
    $('.form-control').on('focus', function() {
        $(this).parent().addClass('focused');
    }).on('blur', function() {
        $(this).parent().removeClass('focused');
    });
    
    // Password strength checker
    $('#password').on('input', function() {
        const password = $(this).val();
        if (password.length > 0) {
            checkPasswordStrength(password);
            $('#passwordStrength').show();
        } else {
            $('#passwordStrength').hide();
        }
    });
    
    // Password confirmation check
    $('#password_confirmation').on('input', function() {
        const password = $('#password').val();
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
    
    // Auto-hide error messages
    setTimeout(function() {
        $('.error-message').fadeOut('slow');
    }, 5000);
});

function checkPasswordStrength(password) {
    let strength = 0;
    let feedback = '';
    
    // Length check
    if (password.length >= 8) strength += 25;
    if (password.length >= 12) strength += 25;
    
    // Character variety checks
    if (/[a-z]/.test(password)) strength += 10;
    if (/[A-Z]/.test(password)) strength += 10;
    if (/[0-9]/.test(password)) strength += 10;
    if (/[^A-Za-z0-9]/.test(password)) strength += 10;
    
    // Update UI
    const $fill = $('#strengthFill');
    const $text = $('#strengthText');
    
    $fill.removeClass('strength-weak strength-fair strength-good strength-strong');
    
    if (strength < 25) {
        $fill.addClass('strength-weak');
        feedback = 'Muito fraca';
    } else if (strength < 50) {
        $fill.addClass('strength-fair');
        feedback = 'Fraca';
    } else if (strength < 75) {
        $fill.addClass('strength-good');
        feedback = 'Boa';
    } else {
        $fill.addClass('strength-strong');
        feedback = 'Forte';
    }
    
    $text.text(`Força da senha: ${feedback}`);
}

function showTerms() {
    alert('Termos de Uso:\n\n1. Você concorda em usar o sistema de forma responsável.\n2. Não compartilhe suas credenciais de acesso.\n3. Respeite a privacidade dos dados.\n4. O sistema é fornecido "como está".\n\nPara mais detalhes, entre em contato conosco.');
}

function showPrivacy() {
    alert('Política de Privacidade:\n\n1. Seus dados são coletados apenas para uso do sistema.\n2. Não compartilhamos suas informações com terceiros.\n3. Você pode solicitar a exclusão de seus dados a qualquer momento.\n4. Utilizamos medidas de segurança para proteger suas informações.\n\nPara mais detalhes, entre em contato conosco.');
}
</script>
@endsection 