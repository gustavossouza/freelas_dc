@extends('layouts.app')

@section('title', 'Editar Produto - Sistema de Vendas')

@section('content')
<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Editar Produto
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('products.update', $product->id) }}" method="POST" id="productForm">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <!-- Basic Information -->
                        <div class="col-md-8">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Informações Básicas
                            </h5>
                            
                            <div class="mb-3">
                                <label for="name" class="form-label">
                                    <i class="fas fa-box me-1"></i>Nome do Produto *
                                </label>
                                <input type="text" 
                                       class="form-control @error('name') is-invalid @enderror" 
                                       id="name" 
                                       name="name" 
                                       value="{{ old('name', $product->name) }}" 
                                       placeholder="Digite o nome do produto"
                                       required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">
                                    <i class="fas fa-align-left me-1"></i>Descrição
                                </label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" 
                                          name="description" 
                                          rows="3" 
                                          placeholder="Descreva o produto">{{ old('description', $product->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <!-- Pricing and Unit -->
                        <div class="col-md-4">
                            <h5 class="mb-3">
                                <i class="fas fa-dollar-sign me-2"></i>
                                Preço e Unidade
                            </h5>
                            
                            <div class="mb-3">
                                <label for="price" class="form-label">
                                    <i class="fas fa-tag me-1"></i>Preço de Venda *
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">R$</span>
                                    <input type="number" 
                                           class="form-control @error('price') is-invalid @enderror" 
                                           id="price" 
                                           name="price" 
                                           value="{{ old('price', $product->price) }}" 
                                           placeholder="0,00"
                                           step="0.01"
                                           min="0"
                                           required>
                                </div>
                                @error('price')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <label for="unit" class="form-label">
                                    <i class="fas fa-ruler me-1"></i>Unidade *
                                </label>
                                <select class="form-select @error('unit') is-invalid @enderror" 
                                        id="unit" 
                                        name="unit" 
                                        required>
                                    <option value="">Selecione a unidade</option>
                                    <option value="unidade" {{ old('unit', $product->unit) == 'unidade' ? 'selected' : '' }}>Unidade</option>
                                    <option value="kg" {{ old('unit', $product->unit) == 'kg' ? 'selected' : '' }}>Quilograma (kg)</option>
                                    <option value="g" {{ old('unit', $product->unit) == 'g' ? 'selected' : '' }}>Grama (g)</option>
                                    <option value="l" {{ old('unit', $product->unit) == 'l' ? 'selected' : '' }}>Litro (L)</option>
                                    <option value="ml" {{ old('unit', $product->unit) == 'ml' ? 'selected' : '' }}>Mililitro (ml)</option>
                                    <option value="m" {{ old('unit', $product->unit) == 'm' ? 'selected' : '' }}>Metro (m)</option>
                                    <option value="cm" {{ old('unit', $product->unit) == 'cm' ? 'selected' : '' }}>Centímetro (cm)</option>
                                    <option value="par" {{ old('unit', $product->unit) == 'par' ? 'selected' : '' }}>Par</option>
                                    <option value="caixa" {{ old('unit', $product->unit) == 'caixa' ? 'selected' : '' }}>Caixa</option>
                                    <option value="pacote" {{ old('unit', $product->unit) == 'pacote' ? 'selected' : '' }}>Pacote</option>
                                    <option value="fardo" {{ old('unit', $product->unit) == 'fardo' ? 'selected' : '' }}>Fardo</option>
                                    <option value="outro" {{ old('unit', $product->unit) == 'outro' ? 'selected' : '' }}>Outro</option>
                                </select>
                                @error('unit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input @error('is_active') is-invalid @enderror" 
                                           type="checkbox" 
                                           id="is_active" 
                                           name="is_active" 
                                           value="1" 
                                           {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">
                                        <i class="fas fa-toggle-on me-1"></i>Produto Ativo
                                    </label>
                                </div>
                                @error('is_active')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Product Preview -->
                    <div class="row">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-eye me-2"></i>
                                Prévia do Produto
                            </h5>
                            <div class="card bg-light">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <h6 id="previewName" class="text-muted">{{ $product->name }}</h6>
                                            <p id="previewDescription" class="text-muted mb-2">{{ $product->description ?: 'Descrição do produto aparecerá aqui...' }}</p>
                                            <small class="text-muted">Unidade: <span id="previewUnit">{{ $product->unit }}</span></small>
                                        </div>
                                        <div class="col-md-4 text-end">
                                            <h5 class="text-primary mb-0">R$ <span id="previewPrice">{{ number_format($product->price, 2, ',', '.') }}</span></h5>
                                            <small class="text-muted">Preço de Venda</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('products.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Voltar
                        </a>
                        <div>
                            <button type="reset" class="btn btn-outline-warning me-2">
                                <i class="fas fa-undo me-2"></i>
                                Restaurar
                            </button>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>
                                Atualizar Produto
                            </button>
                        </div>
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
    
    .form-check-input:checked {
        background-color: var(--primary-color);
        border-color: var(--primary-color);
    }
    
    .card.bg-light {
        border: 1px solid #dee2e6;
    }
</style>
@endsection

@section('scripts')
<script>
$(document).ready(function() {
    // Form validation
    $('#productForm').on('submit', function() {
        showLoading();
    });
    
    // Price formatting
    $('#price').on('input', function() {
        updatePreview();
    });
    
    // Name preview
    $('#name').on('input', function() {
        updatePreview();
    });
    
    // Description preview
    $('#description').on('input', function() {
        updatePreview();
    });
    
    // Unit preview
    $('#unit').on('change', function() {
        updatePreview();
    });
    
    // Update preview function
    function updatePreview() {
        const name = $('#name').val() || 'Nome do Produto';
        const description = $('#description').val() || 'Descrição do produto aparecerá aqui...';
        const price = $('#price').val() || '0,00';
        const unit = $('#unit option:selected').text() || 'unidade';
        
        $('#previewName').text(name);
        $('#previewDescription').text(description);
        $('#previewPrice').text(parseFloat(price).toFixed(2).replace('.', ','));
        $('#previewUnit').text(unit);
    }
    
    // Initialize preview
    updatePreview();
});
</script>
@endsection 