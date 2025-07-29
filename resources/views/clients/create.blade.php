@extends('layouts.app')

@section('title', 'Novo Cliente - Sistema de Vendas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Novo Cliente
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('clients.store') }}" method="POST" id="clientForm">
                    @csrf
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Básicas
                            </h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-user me-1"></i>Nome Completo *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name') }}" 
                                       placeholder="Digite o nome completo"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="email" class="form-label">
                                            <i class="fas fa-envelope me-1"></i>E-mail *
                                        </label>
                                        <input type="email" 
                                               class="form-control @error('email') is-invalid @enderror" 
                                               id="email" 
                                               name="email" 
                                               value="{{ old('email') }}" 
                                               placeholder="exemplo@email.com"
                                               required>
                                        @error('email')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="phone" class="form-label">
                                            <i class="fas fa-phone me-1"></i>Telefone
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('phone') is-invalid @enderror" 
                                               id="phone" 
                                               name="phone" 
                                               value="{{ old('phone') }}" 
                                               placeholder="(11) 99999-9999">
                                        @error('phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="document" class="form-label">
                                    <i class="fas fa-id-card me-1"></i>CPF/CNPJ
                                </label>
                                <input type="text" 
                                       class="form-control @error('document') is-invalid @enderror" 
                                       id="document" 
                                       name="document" 
                                       value="{{ old('document') }}" 
                                       placeholder="000.000.000-00 ou 00.000.000/0000-00">
                                @error('document')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Address Information -->
                        <div class="col-md-4">
                            <h5 class="mb-3">
                                <i class="fas fa-map-marker-alt me-2"></i>
                                Endereço
                            </h5>
                            
                            <div class="mb-3">
                                <label for="address" class="form-label">
                                    <i class="fas fa-home me-1"></i>Endereço
                                </label>
                                <input type="text" 
                                       class="form-control @error('address') is-invalid @enderror" 
                                       id="address" 
                                       name="address" 
                                       value="{{ old('address') }}" 
                                       placeholder="Rua, número, complemento">
                                @error('address')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="city" class="form-label">
                                    <i class="fas fa-city me-1"></i>Cidade
                                </label>
                                <input type="text" 
                                       class="form-control @error('city') is-invalid @enderror" 
                                       id="city" 
                                       name="city" 
                                       value="{{ old('city') }}" 
                                       placeholder="Nome da cidade">
                                @error('city')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="state" class="form-label">
                                            <i class="fas fa-map me-1"></i>Estado
                                        </label>
                                        <select class="form-select @error('state') is-invalid @enderror" 
                                                id="state" 
                                                name="state">
                                            <option value="">Selecione</option>
                                            <option value="AC" {{ old('state') == 'AC' ? 'selected' : '' }}>Acre</option>
                                            <option value="AL" {{ old('state') == 'AL' ? 'selected' : '' }}>Alagoas</option>
                                            <option value="AP" {{ old('state') == 'AP' ? 'selected' : '' }}>Amapá</option>
                                            <option value="AM" {{ old('state') == 'AM' ? 'selected' : '' }}>Amazonas</option>
                                            <option value="BA" {{ old('state') == 'BA' ? 'selected' : '' }}>Bahia</option>
                                            <option value="CE" {{ old('state') == 'CE' ? 'selected' : '' }}>Ceará</option>
                                            <option value="DF" {{ old('state') == 'DF' ? 'selected' : '' }}>Distrito Federal</option>
                                            <option value="ES" {{ old('state') == 'ES' ? 'selected' : '' }}>Espírito Santo</option>
                                            <option value="GO" {{ old('state') == 'GO' ? 'selected' : '' }}>Goiás</option>
                                            <option value="MA" {{ old('state') == 'MA' ? 'selected' : '' }}>Maranhão</option>
                                            <option value="MT" {{ old('state') == 'MT' ? 'selected' : '' }}>Mato Grosso</option>
                                            <option value="MS" {{ old('state') == 'MS' ? 'selected' : '' }}>Mato Grosso do Sul</option>
                                            <option value="MG" {{ old('state') == 'MG' ? 'selected' : '' }}>Minas Gerais</option>
                                            <option value="PA" {{ old('state') == 'PA' ? 'selected' : '' }}>Pará</option>
                                            <option value="PB" {{ old('state') == 'PB' ? 'selected' : '' }}>Paraíba</option>
                                            <option value="PR" {{ old('state') == 'PR' ? 'selected' : '' }}>Paraná</option>
                                            <option value="PE" {{ old('state') == 'PE' ? 'selected' : '' }}>Pernambuco</option>
                                            <option value="PI" {{ old('state') == 'PI' ? 'selected' : '' }}>Piauí</option>
                                            <option value="RJ" {{ old('state') == 'RJ' ? 'selected' : '' }}>Rio de Janeiro</option>
                                            <option value="RN" {{ old('state') == 'RN' ? 'selected' : '' }}>Rio Grande do Norte</option>
                                            <option value="RS" {{ old('state') == 'RS' ? 'selected' : '' }}>Rio Grande do Sul</option>
                                            <option value="RO" {{ old('state') == 'RO' ? 'selected' : '' }}>Rondônia</option>
                                            <option value="RR" {{ old('state') == 'RR' ? 'selected' : '' }}>Roraima</option>
                                            <option value="SC" {{ old('state') == 'SC' ? 'selected' : '' }}>Santa Catarina</option>
                                            <option value="SP" {{ old('state') == 'SP' ? 'selected' : '' }}>São Paulo</option>
                                            <option value="SE" {{ old('state') == 'SE' ? 'selected' : '' }}>Sergipe</option>
                                            <option value="TO" {{ old('state') == 'TO' ? 'selected' : '' }}>Tocantins</option>
                                        </select>
                                        @error('state')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="zip_code" class="form-label">
                                            <i class="fas fa-mail-bulk me-1"></i>CEP
                                        </label>
                                        <input type="text" 
                                               class="form-control @error('zip_code') is-invalid @enderror" 
                                               id="zip_code" 
                                               name="zip_code" 
                                               value="{{ old('zip_code') }}" 
                                               placeholder="00000-000">
                                        @error('zip_code')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Additional Information -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-sticky-note me-2"></i>
                                Informações Adicionais
                            </h5>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-comment me-1"></i>Observações
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Observações sobre o cliente">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Voltar
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>
                            Salvar Cliente
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .form-control:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
    
    .form-select:focus {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#clientForm').on('submit', function() {
        showLoading();
    });
    
    // Phone mask
    $('#phone').mask('(00) 00000-0000');
    
    // Document mask (CPF or CNPJ)
    $('#document').on('input', function() {
        let value = $(this).val().replace(/\D/g, '');
        
        if (value.length <= 11) {
            // CPF mask
            $(this).mask('000.000.000-00');
        } else {
            // CNPJ mask
            $(this).mask('00.000.000/0000-00');
        }
    });
    
    // CEP mask
    $('#zip_code').mask('00000-000');
    
    // Auto-fill address by CEP (optional)
    $('#zip_code').on('blur', function() {
        const cep = $(this).val().replace(/\D/g, '');
        
        if (cep.length === 8) {
            // You can implement CEP API integration here
            // For now, just show a placeholder
            console.log('CEP:', cep);
        }
    });
    
    // Email validation
    $('#email').on('blur', function() {
        const email = $(this).val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        
        if (email && !emailRegex.test(email)) {
            $(this).addClass('is-invalid');
            if (!$(this).next('.invalid-feedback').length) {
                $(this).after('<div class="invalid-feedback">E-mail inválido.</div>');
            }
        } else {
            $(this).removeClass('is-invalid');
            $(this).next('.invalid-feedback').remove();
        }
    });
});
</script>
@endsection 