@extends('layouts.app')

@section('title', 'Nova Venda - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-plus me-2"></i>
                    Nova Venda
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('sells.store') }}" method="POST" id="sellForm">
                    @csrf
                    
                    <!-- Client Selection -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-user me-2"></i>
                                Cliente
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="client_id" class="form-label">
                                            <i class="fas fa-user me-1"></i>Selecionar Cliente *
                                        </label>
                                        <select class="form-select @error('client_id') is-invalid @enderror" 
                                                id="client_id" 
                                                name="client_id" 
                                                required>
                                            <option value="">Selecione um cliente</option>
                                            @foreach($clients ?? [] as $client)
                                                <option value="{{ $client->id }}" {{ old('client_id') == $client->id ? 'selected' : '' }}>
                                                    {{ $client->name }} - {{ $client->email }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('client_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <a href="{{ route('clients.create') }}" class="btn btn-outline-primary w-100" target="_blank">
                                            <i class="fas fa-plus me-2"></i>
                                            Novo Cliente
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Products Section -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-shopping-cart me-2"></i>
                                Produtos
                            </h5>
                            
                            <!-- Product Selection -->
                            <div class="row mb-3">
                                <div class="col-md-8">
                                    <div class="mb-3">
                                        <label for="product_id" class="form-label">
                                            <i class="fas fa-box me-1"></i>Selecionar Produto *
                                        </label>
                                        <select class="form-select @error('product_id') is-invalid @enderror" 
                                                id="product_id" 
                                                name="product_id">
                                            <option value="">Digite para buscar produtos...</option>
                                        </select>
                                        @error('product_id')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="mb-3">
                                        <label class="form-label">&nbsp;</label>
                                        <div class="d-grid gap-2">
                                            <button type="button" class="btn btn-outline-primary" onclick="addSelectedProduct()">
                                                <i class="fas fa-plus me-2"></i>
                                                Adicionar Produto
                                            </button>
                                            <a href="{{ route('products.create') }}" class="btn btn-outline-secondary" target="_blank">
                                                <i class="fas fa-plus me-2"></i>
                                                Novo Produto
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Selected Products -->
                            <div class="table-responsive">
                                <table class="table table-bordered" id="selectedProductsTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Produto</th>
                                            <th width="120">Quantidade</th>
                                            <th width="120">Preço Unit.</th>
                                            <th width="120">Total</th>
                                            <th width="80">Ações</th>
                                        </tr>
                                    </thead>
                                    <tbody id="selectedProductsBody">
                                        <!-- Selected products will be added here -->
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="text-center" id="noProductsMessage">
                                <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                <h5>Nenhum produto selecionado</h5>
                                <p class="text-muted">Busque e adicione produtos à venda.</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Sale Details -->
                    <div class="row mb-4">
                        <div class="col-12">
                            <h5 class="mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                Detalhes da Venda
                            </h5>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="payment_method" class="form-label">
                                            <i class="fas fa-credit-card me-1"></i>Forma de Pagamento
                                        </label>
                                        <div class="input-group">
                                            <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                    id="payment_method" 
                                                    name="payment_method">
                                                <option value="">Selecione a forma de pagamento</option>
                                                <option value="pix" {{ old('payment_method') == 'pix' ? 'selected' : '' }}>PIX</option>
                                                <option value="cartao_debito" {{ old('payment_method') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                                                <option value="cartao_credito" {{ old('payment_method') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito (até 12x)</option>
                                                <option value="boleto" {{ old('payment_method') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                                            </select>
                                            <button type="button" class="btn btn-outline-secondary" onclick="fillCommonPaymentMethods()" title="Preenchimento Rápido">
                                                <i class="fas fa-magic"></i>
                                            </button>
                                        </div>
                                        @error('payment_method')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6" id="installmentsField" style="display: none;">
                                    <div class="mb-3">
                                        <label for="installments" class="form-label">
                                            <i class="fas fa-credit-card me-1"></i>Número de Parcelas
                                        </label>
                                        <div class="input-group">
                                            <select class="form-select @error('installments') is-invalid @enderror" 
                                                    id="installments" 
                                                    name="installments">
                                                <option value="1">1x - À vista</option>
                                                <option value="2">2x</option>
                                                <option value="3">3x</option>
                                                <option value="4">4x</option>
                                                <option value="5">5x</option>
                                                <option value="6">6x</option>
                                                <option value="7">7x</option>
                                                <option value="8">8x</option>
                                                <option value="9">9x</option>
                                                <option value="10">10x</option>
                                                <option value="11">11x</option>
                                                <option value="12">12x</option>
                                            </select>
                                            <button type="button" class="btn btn-outline-secondary" onclick="quickFillInstallments()" title="Configuração Rápida">
                                                <i class="fas fa-bolt"></i>
                                            </button>
                                        </div>
                                        @error('installments')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="sale_date" class="form-label">
                                            <i class="fas fa-calendar me-1"></i>Data da Venda *
                                        </label>
                                        <input type="date" 
                                               class="form-control @error('sale_date') is-invalid @enderror" 
                                               id="sale_date" 
                                               name="sale_date" 
                                               value="{{ old('sale_date', date('Y-m-d')) }}" 
                                               required>
                                        @error('sale_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="due_date" class="form-label">
                                            <i class="fas fa-calendar-alt me-1"></i>Data de Vencimento
                                        </label>
                                        <input type="date" 
                                               class="form-control @error('due_date') is-invalid @enderror" 
                                               id="due_date" 
                                               name="due_date" 
                                               value="{{ old('due_date') }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                            </div>
                            
                            <div class="mb-3">
                                <label for="notes" class="form-label">
                                    <i class="fas fa-sticky-note me-1"></i>Observações
                                </label>
                                <textarea class="form-control @error('notes') is-invalid @enderror" 
                                          id="notes" 
                                          name="notes" 
                                          rows="3" 
                                          placeholder="Observações sobre a venda">{{ old('notes') }}</textarea>
                                @error('notes')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <!-- Form Actions -->
                    <div class="d-flex justify-content-between">
                        <a href="{{ route('sells.index') }}" class="btn btn-secondary">
                            <i class="fas fa-arrow-left me-2"></i>
                            Voltar
                        </a>
                        <button type="submit" class="btn btn-primary" id="submitBtn" disabled>
                            <i class="fas fa-save me-2"></i>
                            Finalizar Venda
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Summary Sidebar -->
    <div class="col-lg-4">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calculator me-2"></i>
                    Resumo da Venda
                </h5>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span>Subtotal:</span>
                    <span id="subtotal">R$ 0,00</span>
                </div>
                <div class="summary-item">
                    <span>Desconto:</span>
                    <div class="input-group input-group-sm">
                        <span class="input-group-text">R$</span>
                        <input type="number" id="discount" name="discount" class="form-control" value="0" step="0.01" min="0">
                    </div>
                </div>

                <hr>
                <div class="summary-item total">
                    <span>Total:</span>
                    <span id="total">R$ 0,00</span>
                </div>
                
                <!-- Installments Summary -->
                <div id="installmentsSummary" style="display: none;">
                    <hr>
                    <div class="summary-item">
                        <span>Parcelas:</span>
                        <span id="installmentsInfo">-</span>
                    </div>
                    <div class="mt-2">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100" onclick="toggleInstallmentsEditor()">
                            <i class="fas fa-edit me-2"></i>
                            Editar Parcelas
                        </button>
                    </div>
                </div>
                

                
                <div class="mt-3">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearSale()">
                            <i class="fas fa-trash me-2"></i>
                            Limpar Venda
                        </button>
                        <button type="button" class="btn btn-outline-info btn-sm" onclick="saveDraft()">
                            <i class="fas fa-save me-2"></i>
                            Salvar Rascunho
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Installments Editor Modal -->
<div class="modal fade" id="installmentsModal" tabindex="-1" aria-labelledby="installmentsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="installmentsModalLabel">
                    <i class="fas fa-credit-card me-2"></i>
                    Gerenciar Parcelas
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Resumo
                                </h6>
                                <div class="row">
                                    <div class="col-6">
                                        <small class="text-muted">Total da Venda:</small>
                                        <div class="fw-bold" id="modalTotalAmount">R$ 0,00</div>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Total Parcelas:</small>
                                        <div class="fw-bold" id="modalInstallmentsTotal">R$ 0,00</div>
                                    </div>
                                </div>
                                <div class="mt-2">
                                    <small class="text-muted">Diferença:</small>
                                    <div class="fw-bold" id="modalDifference">R$ 0,00</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">
                                <i class="fas fa-lightbulb me-2"></i>
                                Dica de Uso
                            </h6>
                            <p class="mb-2 small">
                                <strong>Edição Manual:</strong> Clique em qualquer valor para editá-lo. As parcelas seguintes serão recalculadas automaticamente.
                            </p>
                            <p class="mb-0 small">
                                <strong>Recalcular:</strong> Distribui a diferença proporcionalmente entre as parcelas não editadas.
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-md-12">
                        <div class="d-flex gap-2">
                            <button type="button" class="btn btn-outline-success" onclick="addInstallment()">
                                <i class="fas fa-plus me-2"></i>
                                Adicionar Parcela
                            </button>
                            <button type="button" class="btn btn-outline-primary" onclick="recalculateInstallments()">
                                <i class="fas fa-calculator me-2"></i>
                                Recalcular
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="resetManualEdits()">
                                <i class="fas fa-undo me-2"></i>
                                Resetar Edições
                            </button>
                        </div>
                    </div>
                </div>
                
                <div class="table-responsive">
                    <table class="table table-bordered table-hover" id="installmentsTable">
                        <thead class="table-light">
                            <tr>
                                <th width="80" class="text-center">Parcela</th>
                                <th width="150">Valor</th>
                                <th width="150">Vencimento</th>
                                <th width="100" class="text-center">Ações</th>
                            </tr>
                        </thead>
                        <tbody id="installmentsTableBody">
                            <!-- Installments will be added here -->
                        </tbody>
                    </table>
                </div>
                
                <div class="text-center" id="noInstallmentsMessage">
                    <i class="fas fa-credit-card fa-3x text-muted mb-3"></i>
                    <h6>Nenhuma parcela configurada</h6>
                    <p class="text-muted">Clique em "Adicionar Parcela" para começar.</p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="fas fa-times me-2"></i>
                    Cancelar
                </button>
                <button type="button" class="btn btn-primary" onclick="saveInstallments()">
                    <i class="fas fa-save me-2"></i>
                    Salvar Parcelas
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .installment-amount.border-warning {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .table-warning {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }
    
    .summary-item.total {
        font-size: 1.2rem;
        font-weight: bold;
        color: var(--primary-color);
    }
    
    .product-item {
        cursor: pointer;
        padding: 0.75rem;
        border: 1px solid #dee2e6;
        border-radius: 8px;
        margin-bottom: 0.5rem;
        transition: all 0.3s ease;
    }
    
    .product-item:hover {
        background-color: rgba(99, 102, 241, 0.05);
        border-color: var(--primary-color);
    }
    
    .product-item.selected {
        background-color: rgba(99, 102, 241, 0.1);
        border-color: var(--primary-color);
    }
    
    .quantity-input {
        width: 80px;
        text-align: center;
    }
    
    .price-input {
        width: 100px;
        text-align: right;
    }
    
    .table th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }
    
    .btn-remove-product {
        color: #dc3545;
        border: none;
        background: none;
        padding: 0.25rem 0.5rem;
    }
    
    .btn-remove-product:hover {
        color: #c82333;
    }
    
    .installment-amount,
    .installment-date {
        font-size: 0.875rem;
    }
    
    #installmentsEditor {
        background-color: #f8f9fa;
        border-radius: 8px;
        padding: 1rem;
        margin-top: 1rem;
    }
    
    #installmentsTable {
        font-size: 0.875rem;
    }
    
    #installmentsTable th {
        font-size: 0.8rem;
        font-weight: 600;
    }
    
    .modal-lg {
        max-width: 900px;
    }
    
    .modal-body {
        max-height: 70vh;
        overflow-y: auto;
    }
    
    #installmentsTable .form-control {
        font-size: 0.875rem;
    }
    
    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }
    
    /* Select2 custom styles */
    .select2-container {
        width: 100% !important;
    }
    
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        padding-left: 12px;
    }
    
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }
    
    .select2-dropdown {
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }
    
    .select2-search__field {
        border: 1px solid #ced4da !important;
        border-radius: 0.375rem !important;
        padding: 8px 12px !important;
    }
</style>
@endsection

@section('scripts')
<!-- Select2 JS -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
let selectedProducts = [];
let allProducts = [];
let installmentsData = [];

$(document).ready(function() {
    // Initialize product select with Select2
    $('#product_id').select2({
        placeholder: 'Digite para buscar produtos...',
        allowClear: true,
        ajax: {
            url: '{{ route("products.getProducts") }}',
            dataType: 'json',
            delay: 250,
            data: function(params) {
                return {
                    search: params.term || '',
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                return {
                    results: data.map(function(product) {
                        return {
                            id: product.id,
                            text: product.name + ' - R$ ' + parseFloat(product.price).toFixed(2).replace('.', ',') + ' (' + product.unit + ')',
                            product: product
                        };
                    }),
                    pagination: {
                        more: false
                    }
                };
            },
            cache: true
        },
        minimumInputLength: 1,
        templateResult: formatProductOption,
        templateSelection: formatProductSelection,
        width: '100%'
    });
    
    // Load products for reference
    loadProducts();
    
    // Payment method change
    $('#payment_method').on('change', function() {
        const paymentMethod = $(this).val();
        const installmentsField = $('#installmentsField');
        const installmentsSummary = $('#installmentsSummary');
        
        if (paymentMethod === 'cartao_credito') {
            installmentsField.show();
            updateInstallmentsSummary();
        } else {
            installmentsField.hide();
            installmentsSummary.hide();
            // Clear installments data when payment method is not credit card
            installmentsData = [];
            updateInstallmentsTable();
            updateModalSummary();
        }
    });
    
    // Installments change
    $('#installments').on('change', function() {
        const installments = parseInt($(this).val());
        const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
        
        if (installments > 1 && total > 0) {
            generateInstallments(total, installments);
        } else {
            installmentsData = [];
            updateInstallmentsTable();
            updateModalSummary();
        }
        
        updateInstallmentsSummary();
    });
    
    // Discount calculation
    $('#discount').on('input', function() {
        calculateTotals();
    });
    
    // Form validation
    $('#sellForm').on('submit', function(e) {
        if (selectedProducts.length === 0) {
            e.preventDefault();
            alert('Adicione pelo menos um produto à venda.');
            return false;
        }
        
        // Add hidden inputs for selected products
        selectedProducts.forEach((product, index) => {
            $(this).append(`
                <input type="hidden" name="items[${index}][product_id]" value="${product.id}">
                <input type="hidden" name="items[${index}][product_name]" value="${product.name}">
                <input type="hidden" name="items[${index}][description]" value="${product.description || ''}">
                <input type="hidden" name="items[${index}][quantity]" value="${product.quantity}">
                <input type="hidden" name="items[${index}][unit_price]" value="${product.unit_price}">
                <input type="hidden" name="items[${index}][total_price]" value="${product.total_price}">
            `);
        });
        
        // Add hidden inputs for custom installments
        if (installmentsData.length > 0) {
            installmentsData.forEach((installment, index) => {
                $(this).append(`
                    <input type="hidden" name="custom_installments[${index}][number]" value="${installment.number}">
                    <input type="hidden" name="custom_installments[${index}][amount]" value="${installment.amount}">
                    <input type="hidden" name="custom_installments[${index}][due_date]" value="${installment.dueDate}">
                `);
            });
        }
        
        showLoading();
    });
    
    // Auto-fill common fields
    autoFillCommonFields();
});

function loadProducts() {
    $.ajax({
        url: '{{ route("products.getProducts") }}',
        method: 'GET',
        success: function(response) {
            allProducts = response;
        },
        error: function() {
            // Silent error - not critical for Select2 functionality
        }
    });
}

function formatProductOption(product) {
    if (product.loading) {
        return product.text;
    }
    
    if (!product.id) {
        return product.text;
    }
    
    const productData = product.product;
    return $(`
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <strong>${productData.name}</strong>
                <br>
                <small class="text-muted">${productData.unit}</small>
            </div>
            <div class="text-end">
                <strong class="text-success">R$ ${parseFloat(productData.price).toFixed(2).replace('.', ',')}</strong>
            </div>
        </div>
    `);
}

function formatProductSelection(product) {
    if (!product.id) {
        return product.text;
    }
    
    const productData = product.product;
    return productData.name + ' - R$ ' + parseFloat(productData.price).toFixed(2).replace('.', ',');
}

function addSelectedProduct() {
    const selectedProduct = $('#product_id').select2('data')[0];
    
    if (!selectedProduct || !selectedProduct.id) {
        Swal.fire({
            title: 'Atenção!',
            text: 'Selecione um produto para adicionar.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    const productData = selectedProduct.product;
    
    // Check if product is already added
    const existingProduct = selectedProducts.find(p => p.id === productData.id);
    if (existingProduct) {
        Swal.fire({
            title: 'Produto já adicionado!',
            text: 'Este produto já está na lista de vendas.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Add product to selected products
    const product = {
        id: productData.id,
        name: productData.name,
        description: productData.description || '',
        unit_price: parseFloat(productData.price),
        quantity: 1,
        total_price: parseFloat(productData.price),
        unit: productData.unit
    };
    
    selectedProducts.push(product);
    updateSelectedProductsTable();
    calculateTotals();
    
    // Clear selection
    $('#product_id').val(null).trigger('change');
    
    // Show success message
    Swal.fire({
        title: 'Produto adicionado!',
        text: `${product.name} foi adicionado à venda.`,
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function addProduct(productId) {
    const product = allProducts.find(p => p.id === productId);
    if (!product) return;
    
    // Check if product is already added
    const existingIndex = selectedProducts.findIndex(p => p.id === productId);
    if (existingIndex !== -1) {
        // Increase quantity
        selectedProducts[existingIndex].quantity += 1;
        selectedProducts[existingIndex].total_price = selectedProducts[existingIndex].quantity * selectedProducts[existingIndex].unit_price;
    } else {
        // Add new product
        selectedProducts.push({
            id: product.id,
            name: product.name,
            description: product.description || '',
            quantity: 1,
            unit_price: parseFloat(product.price),
            total_price: parseFloat(product.price),
            unit: product.unit
        });
    }
    
    updateSelectedProductsTable();
    updateTotals();
}

function updateSelectedProductsTable() {
    const tbody = $('#selectedProductsBody');
    const noProductsMessage = $('#noProductsMessage');
    
    if (selectedProducts.length === 0) {
        tbody.empty();
        noProductsMessage.show();
        $('#submitBtn').prop('disabled', true);
        return;
    }
    
    noProductsMessage.hide();
    $('#submitBtn').prop('disabled', false);
    
    let html = '';
    selectedProducts.forEach((product, index) => {
        html += `
            <tr>
                <td>
                    <div>
                        <strong>${product.name}</strong>
                        ${product.description ? `<br><small class="text-muted">${product.description}</small>` : ''}
                    </div>
                </td>
                <td>
                    <input type="number" 
                           class="form-control quantity-input" 
                           value="${product.quantity}" 
                           min="1" 
                           onchange="updateQuantity(${index}, this.value)">
                    <small class="text-muted">${product.unit || 'unidade'}</small>
                </td>
                <td>
                    <input type="number" 
                           class="form-control price-input" 
                           value="${product.unit_price.toFixed(2)}" 
                           step="0.01" 
                           min="0"
                           onchange="updatePrice(${index}, this.value)">
                </td>
                <td class="text-end">
                    <strong class="text-success">R$ ${product.total_price.toFixed(2).replace('.', ',')}</strong>
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn-remove-product" 
                            onclick="removeProduct(${index})"
                            title="Remover produto">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.html(html);
}

function updateQuantity(index, quantity) {
    quantity = parseInt(quantity);
    if (quantity < 1) quantity = 1;
    
    selectedProducts[index].quantity = quantity;
    selectedProducts[index].total_price = quantity * selectedProducts[index].unit_price;
    
    updateSelectedProductsTable();
    calculateTotals();
}

function updatePrice(index, price) {
    price = parseFloat(price);
    if (price < 0) price = 0;
    
    selectedProducts[index].unit_price = price;
    selectedProducts[index].total_price = selectedProducts[index].quantity * price;
    
    updateSelectedProductsTable();
    calculateTotals();
}

function removeProduct(index) {
    selectedProducts.splice(index, 1);
    updateSelectedProductsTable();
    calculateTotals();
}

function calculateTotals() {
    const subtotal = selectedProducts.reduce((sum, product) => sum + product.total_price, 0);
    const discount = parseFloat($('#discount').val()) || 0;
    const total = subtotal - discount;
    
    $('#subtotal').text(`R$ ${subtotal.toFixed(2).replace('.', ',')}`);
    $('#total').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
    
    // Update installments summary if credit card is selected
    if ($('#payment_method').val() === 'cartao_credito') {
        updateInstallmentsSummary();
    }
}

function updateInstallmentsSummary() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installments = parseInt($('#installments').val()) || 1;
    const installmentValue = total / installments;
    
    const installmentsSummary = $('#installmentsSummary');
    const installmentsInfo = $('#installmentsInfo');
    
    if (total > 0 && installments > 0) {
        installmentsInfo.text(`${installments}x - R$ ${installmentValue.toFixed(2).replace('.', ',')}`);
        installmentsSummary.show();
        
        // Generate installments if not already generated
        if (installmentsData.length === 0) {
            generateInstallments(total, installments);
        }
    } else {
        installmentsSummary.hide();
        $('#installmentsEditor').hide();
    }
}

// Installments management

function generateInstallments(total, installments) {
    // Clear existing installments and regenerate
    installmentsData = [];
    const baseAmount = total / installments;
    const firstDueDate = $('#due_date').val() ? new Date($('#due_date').val()) : new Date();
    
    for (let i = 1; i <= installments; i++) {
        const dueDate = new Date(firstDueDate);
        dueDate.setMonth(dueDate.getMonth() + (i - 1));
        
        installmentsData.push({
            number: i,
            amount: baseAmount,
            dueDate: dueDate.toISOString().split('T')[0],
            manuallyEdited: false
        });
    }
    
    updateInstallmentsTable();
    updateModalSummary();
}

function toggleInstallmentsEditor() {
    // Update modal with current data
    updateModalSummary();
    updateInstallmentsTable();
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('installmentsModal'));
    modal.show();
}

function updateInstallmentsTable() {
    const tbody = $('#installmentsTableBody');
    const noInstallmentsMessage = $('#noInstallmentsMessage');
    
    if (installmentsData.length === 0) {
        tbody.empty();
        noInstallmentsMessage.show();
        return;
    }
    
    noInstallmentsMessage.hide();
    
    let html = '';
    installmentsData.forEach((installment, index) => {
        const isManuallyEdited = installment.manuallyEdited;
        const rowClass = isManuallyEdited ? 'table-warning' : '';
        const inputClass = isManuallyEdited ? 'form-control-sm installment-amount border-warning' : 'form-control-sm installment-amount';
        
        html += `
            <tr class="${rowClass}">
                <td class="text-center">
                    <strong>${installment.number}</strong>
                    ${isManuallyEdited ? '<br><small class="text-warning"><i class="fas fa-edit"></i> Editada</small>' : ''}
                </td>
                <td>
                    <input type="number" 
                           class="${inputClass}" 
                           value="${installment.amount.toFixed(2)}" 
                           step="0.01" 
                           min="0"
                           onchange="updateInstallmentAmount(${index}, this.value)"
                           title="${isManuallyEdited ? 'Parcela editada manualmente' : 'Clique para editar'}">
                </td>
                <td>
                    <input type="date" 
                           class="form-control form-control-sm installment-date" 
                           value="${installment.dueDate}"
                           onchange="updateInstallmentDate(${index}, this.value)">
                </td>
                <td class="text-center">
                    <button type="button" 
                            class="btn btn-sm btn-outline-danger" 
                            onclick="removeInstallment(${index})"
                            title="Remover parcela">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });
    
    tbody.html(html);
}

function updateInstallmentAmount(index, amount) {
    amount = parseFloat(amount);
    if (amount < 0) amount = 0;
    
    installmentsData[index].amount = amount;
    installmentsData[index].manuallyEdited = true;
    
    // Mark all subsequent installments as not manually edited
    for (let i = index + 1; i < installmentsData.length; i++) {
        installmentsData[i].manuallyEdited = false;
    }
    
    recalculateInstallments();
    updateModalSummary();
}

function updateInstallmentDate(index, date) {
    installmentsData[index].dueDate = date;
}

function removeInstallment(index) {
    if (installmentsData.length <= 1) {
        Swal.fire({
            title: 'Atenção!',
            text: 'É necessário ter pelo menos uma parcela.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    installmentsData.splice(index, 1);
    
    // Renumber installments
    installmentsData.forEach((installment, i) => {
        installment.number = i + 1;
    });
    
    updateInstallmentsTable();
    recalculateInstallments();
    updateModalSummary();
}

function recalculateInstallments() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const currentTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    
    if (Math.abs(total - currentTotal) > 0.01) {
        // Find the last manually edited installment
        let lastEditedIndex = -1;
        for (let i = installmentsData.length - 1; i >= 0; i--) {
            if (installmentsData[i].manuallyEdited) {
                lastEditedIndex = i;
                break;
            }
        }
        
        // If no manual edits, distribute proportionally
        if (lastEditedIndex === -1) {
            const difference = total - currentTotal;
            const totalAmount = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            
            installmentsData.forEach(installment => {
                const proportion = installment.amount / totalAmount;
                installment.amount += difference * proportion;
            });
        } else {
            // Recalculate from the next installment after the last edited one
            const difference = total - currentTotal;
            const remainingInstallments = installmentsData.slice(lastEditedIndex + 1);
            
            if (remainingInstallments.length > 0) {
                const remainingTotal = remainingInstallments.reduce((sum, installment) => sum + installment.amount, 0);
                
                remainingInstallments.forEach((installment, index) => {
                    const proportion = installment.amount / remainingTotal;
                    const installmentIndex = lastEditedIndex + 1 + index;
                    installmentsData[installmentIndex].amount += difference * proportion;
                });
            }
        }
        
        updateInstallmentsTable();
    }
    
    // Update installments summary
    updateInstallmentsSummaryDisplay();
}

function updateInstallmentsSummaryDisplay() {
    if (installmentsData.length > 0) {
        const totalAmount = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
        const installmentsInfo = $('#installmentsInfo');
        installmentsInfo.text(`${installmentsData.length}x - Total: R$ ${totalAmount.toFixed(2).replace('.', ',')}`);
    }
}

function updateModalSummary() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = total - installmentsTotal;
    
    $('#modalTotalAmount').text(`R$ ${total.toFixed(2).replace('.', ',')}`);
    $('#modalInstallmentsTotal').text(`R$ ${installmentsTotal.toFixed(2).replace('.', ',')}`);
    $('#modalDifference').text(`R$ ${difference.toFixed(2).replace('.', ',')}`);
    
    // Color code the difference
    if (Math.abs(difference) < 0.01) {
        $('#modalDifference').removeClass('text-danger text-warning').addClass('text-success');
    } else if (difference > 0) {
        $('#modalDifference').removeClass('text-success text-warning').addClass('text-danger');
    } else {
        $('#modalDifference').removeClass('text-success text-danger').addClass('text-warning');
    }
}

function saveInstallments() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = Math.abs(total - installmentsTotal);
    
    if (difference > 0.01) {
        Swal.fire({
            title: 'Atenção!',
            text: `O total das parcelas (R$ ${installmentsTotal.toFixed(2).replace('.', ',')}) não confere com o total da venda (R$ ${total.toFixed(2).replace('.', ',')}). Deseja continuar mesmo assim?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, continuar',
            cancelButtonText: 'Recalcular'
        }).then((result) => {
            if (result.isConfirmed) {
                closeModal();
            }
        });
    } else {
        closeModal();
    }
}

function closeModal() {
    updateInstallmentsSummaryDisplay();
    const modal = bootstrap.Modal.getInstance(document.getElementById('installmentsModal'));
    modal.hide();
    
    Swal.fire({
        title: 'Parcelas Salvas!',
        text: 'As parcelas foram configuradas com sucesso.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function resetManualEdits() {
    Swal.fire({
        title: 'Resetar Edições Manuais',
        text: 'Tem certeza que deseja resetar todas as edições manuais das parcelas?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, resetar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
            const baseAmount = total / installmentsData.length;
            
            installmentsData.forEach(installment => {
                installment.amount = baseAmount;
                installment.manuallyEdited = false;
            });
            
            updateInstallmentsTable();
            updateModalSummary();
            
            Swal.fire({
                title: 'Resetado!',
                text: 'Todas as edições manuais foram resetadas.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

// Auto-fill functions
function autoFillCommonFields() {
    // Auto-fill sale date with today's date
    if (!$('#sale_date').val()) {
        $('#sale_date').val(new Date().toISOString().split('T')[0]);
    }
    
    // Auto-fill due date with next month
    if (!$('#due_date').val()) {
        const nextMonth = new Date();
        nextMonth.setMonth(nextMonth.getMonth() + 1);
        $('#due_date').val(nextMonth.toISOString().split('T')[0]);
    }
    
    // Set default payment method if not selected
    if (!$('#payment_method').val()) {
        $('#payment_method').val('pix');
    }
    
    // Set default installments
    if (!$('#installments').val()) {
        $('#installments').val('1');
    }
}

function quickFillInstallments() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    
    if (total <= 0) {
        Swal.fire({
            title: 'Atenção!',
            text: 'Adicione produtos à venda primeiro para configurar parcelas.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    // Show quick fill options
    Swal.fire({
        title: 'Configuração Rápida de Parcelas',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Número de Parcelas:</label>
                    <select id="quickInstallments" class="form-select">
                        <option value="1">1x - À vista</option>
                        <option value="2">2x</option>
                        <option value="3">3x</option>
                        <option value="6">6x</option>
                        <option value="12">12x</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Primeira Parcela:</label>
                    <input type="date" id="quickFirstDue" class="form-control" value="${$('#due_date').val() || new Date().toISOString().split('T')[0]}">
                </div>
                <div class="mb-3">
                    <label class="form-label">Forma de Pagamento:</label>
                    <select id="quickPaymentMethod" class="form-select">
                        <option value="cartao_credito">Cartão de Crédito</option>
                        <option value="boleto">Boleto</option>
                    </select>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Configurar',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const installments = document.getElementById('quickInstallments').value;
            const firstDue = document.getElementById('quickFirstDue').value;
            const paymentMethod = document.getElementById('quickPaymentMethod').value;
            
            if (!firstDue) {
                Swal.showValidationMessage('Selecione a data da primeira parcela');
                return false;
            }
            
            return { installments, firstDue, paymentMethod };
        }
    }).then((result) => {
        if (result.isConfirmed) {
            const { installments, firstDue, paymentMethod } = result.value;
            
            // Update form fields
            $('#payment_method').val(paymentMethod);
            $('#installments').val(installments);
            $('#due_date').val(firstDue);
            
            // Trigger change events
            $('#payment_method').trigger('change');
            $('#installments').trigger('change');
            
            // Generate installments
            if (installments > 1) {
                generateInstallments(total, parseInt(installments));
                updateInstallmentsSummary();
                
                // Show success message
                Swal.fire({
                    title: 'Parcelas Configuradas!',
                    text: `${installments}x parcelas foram configuradas automaticamente.`,
                    icon: 'success',
                    timer: 2000,
                    showConfirmButton: false
                });
            }
        }
    });
}

function fillCommonPaymentMethods() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    
    if (total <= 0) {
        Swal.fire({
            title: 'Atenção!',
            text: 'Adicione produtos à venda primeiro.',
            icon: 'warning',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Preenchimento Rápido',
        html: `
            <div class="text-start">
                <div class="mb-3">
                    <label class="form-label">Selecione uma opção:</label>
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-outline-primary" onclick="fillPixPayment()">
                            <i class="fas fa-qrcode me-2"></i>PIX - Pagamento à vista
                        </button>
                        <button type="button" class="btn btn-outline-success" onclick="fillCreditCardPayment()">
                            <i class="fas fa-credit-card me-2"></i>Cartão de Crédito - 6x
                        </button>
                        <button type="button" class="btn btn-outline-warning" onclick="fillBoletoPayment()">
                            <i class="fas fa-barcode me-2"></i>Boleto - 30 dias
                        </button>
                        <button type="button" class="btn btn-outline-info" onclick="fillDebitCardPayment()">
                            <i class="fas fa-credit-card me-2"></i>Cartão de Débito
                        </button>
                    </div>
                </div>
            </div>
        `,
        showCancelButton: true,
        confirmButtonText: 'Fechar',
        cancelButtonText: 'Cancelar'
    });
}

function fillPixPayment() {
    $('#payment_method').val('pix');
    $('#installments').val('1');
    $('#due_date').val(new Date().toISOString().split('T')[0]);
    
    $('#payment_method').trigger('change');
    Swal.close();
    
    Swal.fire({
        title: 'PIX Configurado!',
        text: 'Pagamento PIX configurado para hoje.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function fillCreditCardPayment() {
    $('#payment_method').val('cartao_credito');
    $('#installments').val('6');
    const nextMonth = new Date();
    nextMonth.setMonth(nextMonth.getMonth() + 1);
    $('#due_date').val(nextMonth.toISOString().split('T')[0]);
    
    $('#payment_method').trigger('change');
    $('#installments').trigger('change');
    Swal.close();
    
    Swal.fire({
        title: 'Cartão de Crédito Configurado!',
        text: '6x no cartão configurado para próximo mês.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function fillBoletoPayment() {
    $('#payment_method').val('boleto');
    $('#installments').val('1');
    const thirtyDays = new Date();
    thirtyDays.setDate(thirtyDays.getDate() + 30);
    $('#due_date').val(thirtyDays.toISOString().split('T')[0]);
    
    $('#payment_method').trigger('change');
    Swal.close();
    
    Swal.fire({
        title: 'Boleto Configurado!',
        text: 'Boleto configurado para 30 dias.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function fillDebitCardPayment() {
    $('#payment_method').val('cartao_debito');
    $('#installments').val('1');
    $('#due_date').val(new Date().toISOString().split('T')[0]);
    
    $('#payment_method').trigger('change');
    Swal.close();
    
    Swal.fire({
        title: 'Cartão de Débito Configurado!',
        text: 'Pagamento no débito configurado para hoje.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function addInstallment() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const newAmount = total / (installmentsData.length + 1);
    
    const lastDueDate = installmentsData.length > 0 ? 
        new Date(installmentsData[installmentsData.length - 1].dueDate) : 
        new Date($('#due_date').val() || Date.now());
    
    lastDueDate.setMonth(lastDueDate.getMonth() + 1);
    
    installmentsData.push({
        number: installmentsData.length + 1,
        amount: newAmount,
        dueDate: lastDueDate.toISOString().split('T')[0]
    });
    
    recalculateInstallments();
    updateInstallmentsTable();
    updateModalSummary();
}

function clearSale() {
    Swal.fire({
        title: 'Limpar Venda?',
        text: 'Tem certeza que deseja limpar todos os produtos da venda?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, limpar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            selectedProducts = [];
            updateSelectedProductsTable();
            calculateTotals();
            $('#sellForm')[0].reset();
            $('#product_id').val(null).trigger('change');
            
            Swal.fire({
                title: 'Venda Limpa!',
                text: 'Todos os produtos foram removidos da venda.',
                icon: 'success',
                timer: 1500,
                showConfirmButton: false
            });
        }
    });
}

function saveDraft() {
    // TODO: Implement draft saving
    alert('Funcionalidade de rascunho será implementada em breve!');
}
</script>
@endsection 