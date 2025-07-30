@extends('layouts.app')

@section('title', 'Editar Venda - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-edit me-2"></i>
                    Editar Venda #{{ $sell->id }}
                </h4>
            </div>
            <div class="card-body">
                <form action="{{ route('sells.update', $sell->id) }}" method="POST" id="sellForm">
                    @csrf
                    @method('PUT')
                    
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
                                                <option value="{{ $client->id }}" {{ old('client_id', $sell->client_id) == $client->id ? 'selected' : '' }}>
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
                                                <option value="pix" {{ old('payment_method', $sell->payment_method) == 'pix' ? 'selected' : '' }}>PIX</option>
                                                <option value="cartao_debito" {{ old('payment_method', $sell->payment_method) == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                                                <option value="cartao_credito" {{ old('payment_method', $sell->payment_method) == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito (até 12x)</option>
                                                <option value="boleto" {{ old('payment_method', $sell->payment_method) == 'boleto' ? 'selected' : '' }}>Boleto</option>
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
                                <div class="col-md-6" id="installmentsField" style="display: {{ $sell->installments->count() > 1 ? 'block' : 'none' }};">
                                    <div class="mb-3">
                                        <label for="installments" class="form-label">
                                            <i class="fas fa-credit-card me-1"></i>Número de Parcelas
                                        </label>
                                        <div class="input-group">
                                            <select class="form-select @error('installments') is-invalid @enderror" 
                                                    id="installments" 
                                                    name="installments">
                                                <option value="1" {{ old('installments', $sell->installments->count() ?: 1) == 1 ? 'selected' : '' }}>1x - À vista</option>
                                                <option value="2" {{ old('installments', $sell->installments->count() ?: 1) == 2 ? 'selected' : '' }}>2x</option>
                                                <option value="3" {{ old('installments', $sell->installments->count() ?: 1) == 3 ? 'selected' : '' }}>3x</option>
                                                <option value="4" {{ old('installments', $sell->installments->count() ?: 1) == 4 ? 'selected' : '' }}>4x</option>
                                                <option value="5" {{ old('installments', $sell->installments->count() ?: 1) == 5 ? 'selected' : '' }}>5x</option>
                                                <option value="6" {{ old('installments', $sell->installments->count() ?: 1) == 6 ? 'selected' : '' }}>6x</option>
                                                <option value="7" {{ old('installments', $sell->installments->count() ?: 1) == 7 ? 'selected' : '' }}>7x</option>
                                                <option value="8" {{ old('installments', $sell->installments->count() ?: 1) == 8 ? 'selected' : '' }}>8x</option>
                                                <option value="9" {{ old('installments', $sell->installments->count() ?: 1) == 9 ? 'selected' : '' }}>9x</option>
                                                <option value="10" {{ old('installments', $sell->installments->count() ?: 1) == 10 ? 'selected' : '' }}>10x</option>
                                                <option value="11" {{ old('installments', $sell->installments->count() ?: 1) == 11 ? 'selected' : '' }}>11x</option>
                                                <option value="12" {{ old('installments', $sell->installments->count() ?: 1) == 12 ? 'selected' : '' }}>12x</option>
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
                                               value="{{ old('sale_date', $sell->sale_date) }}" 
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
                                               value="{{ old('due_date', $sell->due_date) }}">
                                        @error('due_date')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">
                                            <i class="fas fa-info-circle me-1"></i>Status da Venda
                                        </label>
                                        <select class="form-select @error('status') is-invalid @enderror" 
                                                id="status" 
                                                name="status">
                                            <option value="pending" {{ old('status', $sell->status) == 'pending' ? 'selected' : '' }}>Pendente</option>
                                            <option value="completed" {{ old('status', $sell->status) == 'completed' ? 'selected' : '' }}>Concluída</option>
                                            <option value="cancelled" {{ old('status', $sell->status) == 'cancelled' ? 'selected' : '' }}>Cancelada</option>
                                        </select>
                                        @error('status')
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
                                          placeholder="Observações sobre a venda">{{ old('notes', $sell->notes) }}</textarea>
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
                        <button type="submit" class="btn btn-primary" id="submitBtn">
                            <i class="fas fa-save me-2"></i>
                            Atualizar Venda
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
                        <input type="number" id="discount" name="discount" class="form-control" value="{{ old('discount', $sell->discount) }}" step="0.01" min="0">
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
                    <!-- Difference Alert -->
                    <div id="sidebarDifferenceAlert" class="alert alert-warning mt-2" style="display: none;">
                        <small>
                            <i class="fas fa-exclamation-triangle me-1"></i>
                            <span id="sidebarDifferenceText"></span>
                        </small>
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
                            <button type="button" class="btn btn-outline-warning" onclick="autoFixDifference()" id="autoFixBtn" style="display: none;">
                                <i class="fas fa-magic me-2"></i>
                                Corrigir Diferença
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
    
    /* Estilos para diferenças nas parcelas */
    .installment-amount.border-warning {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
        animation: pulse-warning 2s infinite;
    }
    
    .installment-amount.border-danger {
        border-color: #dc3545 !important;
        box-shadow: 0 0 0 0.2rem rgba(220, 53, 69, 0.25);
        animation: pulse-danger 2s infinite;
    }
    
    @keyframes pulse-warning {
        0% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0.25); }
        70% { box-shadow: 0 0 0 10px rgba(255, 193, 7, 0); }
        100% { box-shadow: 0 0 0 0 rgba(255, 193, 7, 0); }
    }
    
    @keyframes pulse-danger {
        0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.25); }
        70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); }
        100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); }
    }
    
    .modal-content.border-warning {
        border-color: #ffc107 !important;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    #tempDifferenceAlert {
        animation: slideInDown 0.3s ease-out;
    }
    
    @keyframes slideInDown {
        from {
            transform: translateY(-100%);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
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

// Load existing sell items
const existingItems = @json($formattedSellItems);

// Load existing installments
const existingInstallments = @json($formattedInstallments);

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
    
    // Load existing sell items
    loadExistingItems();
    
    // Load existing installments from controller data (only once)
    if (existingInstallments && existingInstallments.length > 0 && installmentsData.length === 0) {
        // Set the installments field to the correct number
        $('#installments').val(existingInstallments.length);
        
        // Show installments field if more than 1 installment
        if (existingInstallments.length > 1) {
            $('#installmentsField').show();
        }
        
        existingInstallments.forEach(installment => {
            const installmentData = {
                number: installment.installment_number,
                amount: installment.amount,
                dueDate: installment.due_date,
                id: installment.id,
                manuallyEdited: false
            };
            
            installmentsData.push(installmentData);
        });
        
        updateInstallmentsSummary();
        updateInstallmentsSummaryDisplay();
    }
    
    // Auto-fill common fields
    autoFillCommonFields();
    
    // Add real-time validation for installment amounts
    $(document).on('input', '.installment-amount', function() {
        const index = $(this).closest('tr').index();
        const value = parseFloat($(this).val()) || 0;
        
        if (value !== installmentsData[index].amount) {
            updateInstallmentAmount(index, value);
        }
    });
    
    // Add real-time validation for installment amounts with debounce
    let debounceTimer;
    $(document).on('input', '.installment-amount', function() {
        clearTimeout(debounceTimer);
        const input = $(this);
        const index = input.closest('tr').index();
        
        debounceTimer = setTimeout(() => {
            const value = parseFloat(input.val()) || 0;
            if (value !== installmentsData[index].amount) {
                updateInstallmentAmount(index, value);
            }
        }, 300); // 300ms delay
    });
    
    // Show installments field if there are existing installments
    if (existingInstallments && existingInstallments.length > 1) {
        $('#installmentsField').show();
    }
    
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
        
        // Only generate new installments if there are no existing ones from controller
        if (installments > 1 && total > 0 && installmentsData.length === 0) {
            generateInstallments(total, installments);
        } else if (installments <= 1) {
            installmentsData = [];
            updateInstallmentsTable();
            updateModalSummary();
        } else if (installments > 1 && total > 0 && installmentsData.length > 0) {
            // Se já existem parcelas e a quantidade mudou, recalcular
            const oldCount = installmentsData.length;
            
            if (installments > oldCount) {
                // Adicionar parcelas
                const baseAmount = total / installments;
                const lastDueDate = new Date(installmentsData[oldCount - 1].dueDate);
                
                for (let i = oldCount + 1; i <= installments; i++) {
                    lastDueDate.setMonth(lastDueDate.getMonth() + 1);
                    
                    installmentsData.push({
                        number: i,
                        amount: baseAmount,
                        dueDate: lastDueDate.toISOString().split('T')[0],
                        manuallyEdited: false
                    });
                }
                
                // Recalcular todas as parcelas para distribuir o valor corretamente
                recalculateInstallments();
            } else if (installments < oldCount) {
                // Remover parcelas (manter as primeiras)
                installmentsData = installmentsData.slice(0, installments);
                
                // Renumerar e recalcular
                installmentsData.forEach((installment, index) => {
                    installment.number = index + 1;
                });
                
                recalculateInstallments();
            }
        }
        
        updateInstallmentsSummary();
        
        // Validate if changing installments affects the total
        if (installmentsData.length > 0) {
            const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            const difference = Math.abs(total - installmentsTotal);
            
            if (difference > 0.01) {
                // Mostrar notificação mais sutil
                const statusText = total > installmentsTotal ? 'Faltam' : 'Sobram';
                showTemporaryDifferenceAlert(difference, statusText);
                
                // Atualizar o resumo das parcelas com alerta visual
                const installmentsInfo = $('#installmentsInfo');
                let infoText = installmentsData.length + 'x - Total: R$ ' + installmentsTotal.toFixed(2).replace('.', ',');
                infoText += ' <span class="text-warning"><i class="fas fa-exclamation-triangle"></i> ' + statusText + ': R$ ' + difference.toFixed(2).replace('.', ',') + '</span>';
                installmentsInfo.html(infoText);
            }
        }
    });
    
    // Discount calculation
    $('#discount').on('input', function() {
        calculateTotals();
    });
    
    // Form validation
    $('#sellForm').on('submit', function(e) {
        if (selectedProducts.length === 0) {
            e.preventDefault();
            Swal.fire({
                title: 'Atenção!',
                text: 'Adicione pelo menos um produto à venda.',
                icon: 'warning',
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        // Validate installments total for credit card payments
        const paymentMethod = $('#payment_method').val();
        if (paymentMethod === 'cartao_credito' && installmentsData.length > 0) {
            const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
            const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            const difference = Math.abs(total - installmentsTotal);
            
            if (difference > 0.01) {
                e.preventDefault();
                
                Swal.fire({
                    title: 'Inconsistência nas Parcelas!',
                    html: `
                        <div class="text-start">
                            <p><strong>Método de Pagamento:</strong> Cartão de Crédito</p>
                            <p><strong>Total da Venda:</strong> R$ ${total.toFixed(2).replace('.', ',')}</p>
                            <p><strong>Total das Parcelas:</strong> R$ ${installmentsTotal.toFixed(2).replace('.', ',')}</p>
                            <p><strong>Diferença:</strong> R$ ${difference.toFixed(2).replace('.', ',')}</p>
                            <hr>
                            <p class="text-danger"><i class="fas fa-exclamation-triangle"></i> Não é possível salvar com valores inconsistentes!</p>
                            <p class="text-muted">Ajuste as parcelas antes de continuar.</p>
                        </div>
                    `,
                    icon: 'error',
                    showCancelButton: true,
                    confirmButtonText: 'Forçar Salvamento',
                    cancelButtonText: 'Cancelar',
                    confirmButtonColor: '#dc3545',
                    cancelButtonColor: '#6c757d'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Continue with form submission
                        $('#sellForm')[0].submit();
                    }
                });
                return false;
            }
        }
        
        // Add hidden inputs for selected products
        selectedProducts.forEach((product, index) => {
            $(this).append(
                '<input type="hidden" name="items[' + index + '][product_id]" value="' + product.id + '">' +
                '<input type="hidden" name="items[' + index + '][product_name]" value="' + product.name + '">' +
                '<input type="hidden" name="items[' + index + '][description]" value="' + (product.description || '') + '">' +
                '<input type="hidden" name="items[' + index + '][quantity]" value="' + product.quantity + '">' +
                '<input type="hidden" name="items[' + index + '][unit_price]" value="' + product.unit_price + '">' +
                '<input type="hidden" name="items[' + index + '][total_price]" value="' + product.total_price + '">'
            );
        });
        
        // Add hidden inputs for custom installments
        if (installmentsData.length > 0) {
            installmentsData.forEach((installment, index) => {
                $(this).append(
                    '<input type="hidden" name="custom_installments[' + index + '][number]" value="' + installment.number + '">' +
                    '<input type="hidden" name="custom_installments[' + index + '][amount]" value="' + installment.amount + '">' +
                    '<input type="hidden" name="custom_installments[' + index + '][due_date]" value="' + installment.dueDate + '">'
                );
            });
        }
        
        showLoading();
    });
    
    // Initialize payment method display
    if ($('#payment_method').val() === 'cartao_credito') {
        $('#installmentsField').show();
    }
    
    // Event listener for modal close
    $('#installmentsModal').on('hidden.bs.modal', function() {
        // Limpar notificações quando o modal é fechado
        hideDifferenceNotification();
        $('#autoFixBtn').hide();
        $('#inconsistencyWarning').remove();
        
        // Atualizar o resumo das parcelas no sidebar
        updateInstallmentsSummary();
    });
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

function loadExistingItems() {
    if (existingItems && existingItems.length > 0) {
        existingItems.forEach(item => {
            const product = {
                id: item.product_id || null,
                name: item.product_name,
                description: item.description || '',
                unit_price: parseFloat(item.unit_price),
                quantity: parseInt(item.quantity),
                total_price: parseFloat(item.total_price),
                unit: 'unidade'
            };
            
            selectedProducts.push(product);
        });
        
        updateSelectedProductsTable();
        calculateTotals();
    }
}

function formatProductOption(product) {
    if (product.loading) {
        return product.text;
    }
    
    if (!product.id) {
        return product.text;
    }
    
    const productData = product.product;
    return $(
        '<div class="d-flex justify-content-between align-items-center">' +
            '<div>' +
                '<strong>' + productData.name + '</strong>' +
                '<br>' +
                '<small class="text-muted">' + productData.unit + '</small>' +
            '</div>' +
            '<div class="text-end">' +
                '<strong class="text-success">R$ ' + parseFloat(productData.price).toFixed(2).replace('.', ',') + '</strong>' +
            '</div>' +
        '</div>'
    );
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
        html += 
            '<tr>' +
                '<td>' +
                    '<div>' +
                        '<strong>' + product.name + '</strong>' +
                        (product.description ? '<br><small class="text-muted">' + product.description + '</small>' : '') +
                    '</div>' +
                '</td>' +
                '<td>' +
                    '<input type="number" ' +
                           'class="form-control quantity-input" ' +
                           'value="' + product.quantity + '" ' +
                           'min="1" ' +
                           'onchange="updateQuantity(' + index + ', this.value)">' +
                    '<small class="text-muted">' + (product.unit || 'unidade') + '</small>' +
                '</td>' +
                '<td>' +
                    '<input type="number" ' +
                           'class="form-control price-input" ' +
                           'value="' + product.unit_price.toFixed(2) + '" ' +
                           'step="0.01" ' +
                           'min="0" ' +
                           'onchange="updatePrice(' + index + ', this.value)">' +
                '</td>' +
                '<td class="text-end">' +
                    '<strong class="text-success">R$ ' + product.total_price.toFixed(2).replace('.', ',') + '</strong>' +
                '</td>' +
                '<td class="text-center">' +
                    '<button type="button" ' +
                            'class="btn-remove-product" ' +
                            'onclick="removeProduct(' + index + ')" ' +
                            'title="Remover produto">' +
                        '<i class="fas fa-trash"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
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
    
    $('#subtotal').text('R$ ' + subtotal.toFixed(2).replace('.', ','));
    $('#total').text('R$ ' + total.toFixed(2).replace('.', ','));
    
    // Update installments summary if credit card is selected
    if ($('#payment_method').val() === 'cartao_credito') {
        updateInstallmentsSummary();
        
        // Verificar se há diferença nas parcelas após mudança no total
        if (installmentsData.length > 0) {
            const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            const difference = Math.abs(total - installmentsTotal);
            
            if (difference > 0.01) {
                // Atualizar o resumo das parcelas com alerta visual
                const statusText = total > installmentsTotal ? 'Faltam' : 'Sobram';
                const statusClass = total > installmentsTotal ? 'text-danger' : 'text-warning';
                
                const installmentsInfo = $('#installmentsInfo');
                let infoText = installmentsData.length + 'x - Total: R$ ' + installmentsTotal.toFixed(2).replace('.', ',');
                infoText += ' <span class="' + statusClass + '"><i class="fas fa-exclamation-triangle"></i> ' + statusText + ': R$ ' + difference.toFixed(2).replace('.', ',') + '</span>';
                installmentsInfo.html(infoText);
                
                // Mostrar notificação temporária
                showTemporaryDifferenceAlert(difference, statusText);
            }
        }
    }
}

function showTemporaryDifferenceAlert(difference, statusText) {
    // Remover alerta anterior se existir
    $('#tempDifferenceAlert').remove();
    
    const alertHtml = `
        <div id="tempDifferenceAlert" class="alert alert-warning alert-dismissible fade show mt-2" role="alert">
            <i class="fas fa-exclamation-triangle me-2"></i>
            <strong>Total alterado!</strong> ${statusText} R$ ${difference.toFixed(2).replace('.', ',')} nas parcelas.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    $('#installmentsSummary').after(alertHtml);
    
    // Remover alerta após 5 segundos
    setTimeout(() => {
        $('#tempDifferenceAlert').fadeOut();
    }, 5000);
}

function updateInstallmentsSummary() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installments = parseInt($('#installments').val()) || 1;
    
    const installmentsSummary = $('#installmentsSummary');
    const installmentsInfo = $('#installmentsInfo');
    const sidebarAlert = $('#sidebarDifferenceAlert');
    const sidebarAlertText = $('#sidebarDifferenceText');
    
    if (total > 0 && installments > 0) {
        // If we have existing installments data, use the actual total from installments
        if (installmentsData.length > 0) {
            const actualTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            const difference = total - actualTotal;
            const absDifference = Math.abs(difference);
            
            let infoText = installments + 'x - Total: R$ ' + actualTotal.toFixed(2).replace('.', ',');
            
            if (absDifference > 0.01) {
                const statusClass = difference > 0 ? 'text-danger' : 'text-warning';
                const statusIcon = difference > 0 ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
                const statusText = difference > 0 ? 'Faltam' : 'Sobram';
                
                infoText += ' <span class="' + statusClass + '"><i class="' + statusIcon + '"></i> ' + statusText + ': R$ ' + absDifference.toFixed(2).replace('.', ',') + '</span>';
                
                // Mostrar alerta no sidebar
                sidebarAlert.removeClass('alert-warning alert-danger').addClass(difference > 0 ? 'alert-danger' : 'alert-warning');
                sidebarAlertText.text(`${statusText} R$ ${absDifference.toFixed(2).replace('.', ',')} nas parcelas`);
                sidebarAlert.show();
            } else {
                infoText += ' <span class="text-success"><i class="fas fa-check-circle"></i> OK</span>';
                sidebarAlert.hide();
            }
            
            installmentsInfo.html(infoText);
        } else {
            // Only calculate automatic value if no existing data
            const installmentValue = total / installments;
            installmentsInfo.text(installments + 'x - R$ ' + installmentValue.toFixed(2).replace('.', ','));
            sidebarAlert.hide();
        }
        installmentsSummary.show();
    } else {
        installmentsSummary.hide();
        $('#installmentsEditor').hide();
        sidebarAlert.hide();
    }
}

function loadExistingInstallments() {
    // This function is no longer needed as data is loaded in the main initialization
    // Keeping it empty to avoid breaking any existing calls
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
    
    // Verificar se há diferença antes de abrir o modal
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = Math.abs(total - installmentsTotal);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('installmentsModal'));
    modal.show();
    
    // Se há diferença, mostrar o botão de correção automática
    if (difference > 0.01) {
        $('#autoFixBtn').show();
        
        // Mostrar notificação sutil
        setTimeout(() => {
            const statusText = total > installmentsTotal ? 'Faltam' : 'Sobram';
            showDifferenceNotification(total - installmentsTotal, difference);
        }, 300);
    } else {
        $('#autoFixBtn').hide();
        hideDifferenceNotification();
    }
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
        
        html += 
            '<tr class="' + rowClass + '">' +
                '<td class="text-center">' +
                    '<strong>' + installment.number + '</strong>' +
                    (isManuallyEdited ? '<br><small class="text-warning"><i class="fas fa-edit"></i> Editada</small>' : '') +
                '</td>' +
                '<td>' +
                    '<input type="number" ' +
                           'class="' + inputClass + '" ' +
                           'value="' + installment.amount.toFixed(2) + '" ' +
                           'step="0.01" ' +
                           'min="0" ' +
                           'onchange="updateInstallmentAmount(' + index + ', this.value)" ' +
                           'title="' + (isManuallyEdited ? 'Parcela editada manualmente' : 'Clique para editar') + '">' +
                '</td>' +
                '<td>' +
                    '<input type="date" ' +
                           'class="form-control form-control-sm installment-date" ' +
                           'value="' + installment.dueDate + '" ' +
                           'onchange="updateInstallmentDate(' + index + ', this.value)">' +
                '</td>' +
                '<td class="text-center">' +
                    '<button type="button" ' +
                            'class="btn btn-sm btn-outline-danger" ' +
                            'onclick="removeInstallment(' + index + ')" ' +
                            'title="Remover parcela">' +
                        '<i class="fas fa-trash"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>';
    });
    
    tbody.html(html);
}

function updateInstallmentAmount(index, amount) {
    amount = parseFloat(amount);
    if (amount < 0) amount = 0;
    
    const oldAmount = installmentsData[index].amount;
    installmentsData[index].amount = amount;
    installmentsData[index].manuallyEdited = true;
    
    // Mark all subsequent installments as not manually edited
    for (let i = index + 1; i < installmentsData.length; i++) {
        installmentsData[i].manuallyEdited = false;
    }
    
    // Recalcular automaticamente a diferença
    recalculateInstallments();
    updateModalSummary();
    updateInstallmentsTable(); // Atualiza a tabela para mostrar mudanças visuais
    
    // Atualiza a diferença em tempo real
    updateDifferenceDisplay();
    
    // Mostrar alerta se houver diferença significativa
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = Math.abs(total - installmentsTotal);
    
    if (difference > 0.01) {
        // Destacar visualmente a linha com diferença
        const row = $(`#installmentsTableBody tr:eq(${index})`);
        row.removeClass('table-success').addClass('table-warning');
        
        // Aplicar classe CSS ao input
        const input = row.find('.installment-amount');
        const statusClass = total > installmentsTotal ? 'border-danger' : 'border-warning';
        input.removeClass('border-success border-warning border-danger').addClass(statusClass);
        
        // Mostrar notificação sutil
        const statusText = total > installmentsTotal ? 'Faltam' : 'Sobram';
        
        // Atualizar o resumo no modal com destaque
        $('#modalDifference').removeClass('text-success').addClass(total > installmentsTotal ? 'text-danger' : 'text-warning');
        $('#modalDifference').html(`R$ ${difference.toFixed(2).replace('.', ',')} <i class="fas fa-exclamation-triangle"></i> ${statusText}`);
    } else {
        // Remover destaque se não há diferença
        const row = $(`#installmentsTableBody tr:eq(${index})`);
        row.removeClass('table-warning').addClass('table-success');
        
        // Remover classes CSS do input
        const input = row.find('.installment-amount');
        input.removeClass('border-warning border-danger border-success');
        
        $('#modalDifference').removeClass('text-danger text-warning').addClass('text-success');
        $('#modalDifference').html(`R$ ${difference.toFixed(2).replace('.', ',')} <i class="fas fa-check-circle"></i>`);
    }
}

function updateDifferenceDisplay() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = total - installmentsTotal;
    const absDifference = Math.abs(difference);
    
    // Atualiza o resumo das parcelas com a diferença
    const installmentsInfo = $('#installmentsInfo');
    if (installmentsData.length > 0) {
        let infoText = installmentsData.length + 'x - Total: R$ ' + installmentsTotal.toFixed(2).replace('.', ',');
        
        if (absDifference > 0.01) {
            const statusClass = difference > 0 ? 'text-danger' : 'text-warning';
            const statusIcon = difference > 0 ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            const statusText = difference > 0 ? 'Faltam' : 'Sobram';
            
            infoText += ' <span class="' + statusClass + '"><i class="' + statusIcon + '"></i> ' + statusText + ': R$ ' + absDifference.toFixed(2).replace('.', ',') + '</span>';
            
            // Mostrar notificação visual sutil
            showDifferenceNotification(difference, absDifference);
            
            // Mostrar botão de correção automática
            $('#autoFixBtn').show();
        } else {
            infoText += ' <span class="text-success"><i class="fas fa-check-circle"></i> OK</span>';
            hideDifferenceNotification();
            
            // Ocultar botão de correção automática
            $('#autoFixBtn').hide();
        }
        
        installmentsInfo.html(infoText);
    }
    
    // Atualiza o resumo no modal
    updateModalSummary();
}

function showDifferenceNotification(difference, absDifference) {
    // Remover notificação anterior se existir
    hideDifferenceNotification();
    
    const statusClass = difference > 0 ? 'alert-danger' : 'alert-warning';
    const statusIcon = difference > 0 ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
    const statusText = difference > 0 ? 'Faltam' : 'Sobram';
    
    const notificationHtml = `
        <div id="differenceNotification" class="alert ${statusClass} alert-dismissible fade show mt-3" role="alert">
            <i class="${statusIcon} me-2"></i>
            <strong>Diferença detectada!</strong> ${statusText} R$ ${absDifference.toFixed(2).replace('.', ',')} nas parcelas.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    `;
    
    // Adicionar notificação antes do modal footer
    $('.modal-footer').before(notificationHtml);
    
    // Adicionar classe de destaque ao modal
    $('#installmentsModal .modal-content').addClass('border-warning');
}

function hideDifferenceNotification() {
    $('#differenceNotification').remove();
    $('#installmentsModal .modal-content').removeClass('border-warning');
}

function autoFixDifference() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = total - installmentsTotal;
    const absDifference = Math.abs(difference);
    
    if (absDifference <= 0.01) {
        Swal.fire({
            title: 'Nenhuma diferença!',
            text: 'As parcelas já estão corretas.',
            icon: 'info',
            confirmButtonText: 'OK'
        });
        return;
    }
    
    Swal.fire({
        title: 'Corrigir Diferença Automaticamente?',
        html: `
            <div class="text-start">
                <p><strong>Total da Venda:</strong> R$ ${total.toFixed(2).replace('.', ',')}</p>
                <p><strong>Total das Parcelas:</strong> R$ ${installmentsTotal.toFixed(2).replace('.', ',')}</p>
                <p><strong>Diferença:</strong> R$ ${absDifference.toFixed(2).replace('.', ',')}</p>
                <hr>
                <p class="text-info"><i class="fas fa-info-circle"></i> A diferença será distribuída proporcionalmente entre as parcelas não editadas manualmente.</p>
            </div>
        `,
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sim, corrigir!',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#ffc107'
    }).then((result) => {
        if (result.isConfirmed) {
            // Encontrar a última parcela editada manualmente
            let lastEditedIndex = -1;
            for (let i = installmentsData.length - 1; i >= 0; i--) {
                if (installmentsData[i].manuallyEdited) {
                    lastEditedIndex = i;
                    break;
                }
            }
            
            // Distribuir a diferença nas parcelas não editadas
            if (lastEditedIndex === -1) {
                // Se nenhuma parcela foi editada, distribuir proporcionalmente
                const totalAmount = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
                
                if (totalAmount > 0) {
                    installmentsData.forEach(installment => {
                        const proportion = installment.amount / totalAmount;
                        installment.amount += difference * proportion;
                    });
                } else {
                    // Se todos os valores são zero, distribuir igualmente
                    const equalAmount = total / installmentsData.length;
                    installmentsData.forEach(installment => {
                        installment.amount = equalAmount;
                    });
                }
            } else {
                // Distribuir apenas nas parcelas após a última editada
                const remainingInstallments = installmentsData.slice(lastEditedIndex + 1);
                
                if (remainingInstallments.length > 0) {
                    const remainingTotal = remainingInstallments.reduce((sum, installment) => sum + installment.amount, 0);
                    
                    if (remainingTotal > 0) {
                        remainingInstallments.forEach((installment, index) => {
                            const proportion = installment.amount / remainingTotal;
                            const installmentIndex = lastEditedIndex + 1 + index;
                            installmentsData[installmentIndex].amount += difference * proportion;
                        });
                    } else {
                        // Se os valores restantes são zero, distribuir igualmente
                        const equalAmount = difference / remainingInstallments.length;
                        remainingInstallments.forEach((installment, index) => {
                            const installmentIndex = lastEditedIndex + 1 + index;
                            installmentsData[installmentIndex].amount = equalAmount;
                        });
                    }
                }
            }
            
            // Garantir que nenhum valor seja negativo
            installmentsData.forEach(installment => {
                if (installment.amount < 0) {
                    installment.amount = 0;
                }
            });
            
            // Atualizar a interface
            updateInstallmentsTable();
            updateModalSummary();
            updateDifferenceDisplay();
            
            // Mostrar sucesso
            Swal.fire({
                title: 'Diferença Corrigida!',
                text: 'As parcelas foram ajustadas automaticamente.',
                icon: 'success',
                timer: 2000,
                showConfirmButton: false
            });
        }
    });
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
    const difference = total - currentTotal;
    
    if (Math.abs(difference) > 0.01) {
        // Find the last manually edited installment
        let lastEditedIndex = -1;
        for (let i = installmentsData.length - 1; i >= 0; i--) {
            if (installmentsData[i].manuallyEdited) {
                lastEditedIndex = i;
                break;
            }
        }
        
        // If no manual edits, distribute proportionally among all installments
        if (lastEditedIndex === -1) {
            const totalAmount = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
            
            if (totalAmount > 0) {
                installmentsData.forEach(installment => {
                    const proportion = installment.amount / totalAmount;
                    installment.amount += difference * proportion;
                });
            } else {
                // If all amounts are zero, distribute equally
                const equalAmount = total / installmentsData.length;
                installmentsData.forEach(installment => {
                    installment.amount = equalAmount;
                });
            }
        } else {
            // Recalculate from the next installment after the last edited one
            const remainingInstallments = installmentsData.slice(lastEditedIndex + 1);
            
            if (remainingInstallments.length > 0) {
                const remainingTotal = remainingInstallments.reduce((sum, installment) => sum + installment.amount, 0);
                
                if (remainingTotal > 0) {
                    remainingInstallments.forEach((installment, index) => {
                        const proportion = installment.amount / remainingTotal;
                        const installmentIndex = lastEditedIndex + 1 + index;
                        installmentsData[installmentIndex].amount += difference * proportion;
                    });
                } else {
                    // If remaining amounts are zero, distribute equally
                    const equalAmount = difference / remainingInstallments.length;
                    remainingInstallments.forEach((installment, index) => {
                        const installmentIndex = lastEditedIndex + 1 + index;
                        installmentsData[installmentIndex].amount = equalAmount;
                    });
                }
            }
        }
        
        // Garantir que nenhum valor seja negativo
        installmentsData.forEach(installment => {
            if (installment.amount < 0) {
                installment.amount = 0;
            }
        });
        
        updateInstallmentsTable();
    }
    
    // Update installments summary
    updateInstallmentsSummaryDisplay();
    
    // Atualizar o resumo no modal
    updateModalSummary();
}

function updateInstallmentsSummaryDisplay() {
    if (installmentsData.length > 0) {
        const totalAmount = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
        const installmentsInfo = $('#installmentsInfo');
        installmentsInfo.text(installmentsData.length + 'x - Total: R$ ' + totalAmount.toFixed(2).replace('.', ','));
    }
}

function updateModalSummary() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = total - installmentsTotal;
    
    $('#modalTotalAmount').text('R$ ' + total.toFixed(2).replace('.', ','));
    $('#modalInstallmentsTotal').text('R$ ' + installmentsTotal.toFixed(2).replace('.', ','));
    $('#modalDifference').text('R$ ' + difference.toFixed(2).replace('.', ','));
    
    // Color code the difference with enhanced visual feedback
    const absDifference = Math.abs(difference);
    if (absDifference < 0.01) {
        $('#modalDifference').removeClass('text-danger text-warning').addClass('text-success');
        $('#modalDifference').html('R$ ' + difference.toFixed(2).replace('.', ',') + ' <i class="fas fa-check-circle text-success"></i>');
        $('#modalDifference').parent().removeClass('bg-light bg-warning bg-danger').addClass('bg-success bg-opacity-10');
    } else if (difference > 0) {
        $('#modalDifference').removeClass('text-success text-warning').addClass('text-danger');
        $('#modalDifference').html('R$ ' + difference.toFixed(2).replace('.', ',') + ' <i class="fas fa-exclamation-triangle text-danger"></i> Faltam');
        $('#modalDifference').parent().removeClass('bg-light bg-success bg-warning').addClass('bg-danger bg-opacity-10');
    } else {
        $('#modalDifference').removeClass('text-success text-danger').addClass('text-warning');
        $('#modalDifference').html('R$ ' + absDifference.toFixed(2).replace('.', ',') + ' <i class="fas fa-exclamation-triangle text-warning"></i> Sobram');
        $('#modalDifference').parent().removeClass('bg-light bg-success bg-danger').addClass('bg-warning bg-opacity-10');
    }
    
    // Show enhanced warning if there's a significant difference
    if (absDifference > 0.01) {
        if (!$('#inconsistencyWarning').length) {
            const statusClass = difference > 0 ? 'alert-danger' : 'alert-warning';
            const statusText = difference > 0 ? 'Faltam' : 'Sobram';
            const statusIcon = difference > 0 ? 'fas fa-exclamation-triangle' : 'fas fa-info-circle';
            
            const warningHtml = `
                <div id="inconsistencyWarning" class="alert ${statusClass} alert-dismissible fade show mt-3" role="alert">
                    <i class="${statusIcon} me-2"></i>
                    <strong>Atenção!</strong> O total das parcelas não confere com o total da venda.
                    <br><strong>${statusText}:</strong> R$ ${absDifference.toFixed(2).replace('.', ',')}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            `;
            $('.modal-body').append(warningHtml);
        }
    } else {
        $('#inconsistencyWarning').remove();
    }
    
    // Atualizar o resumo das parcelas no sidebar
    updateInstallmentsSummary();
}

function saveInstallments() {
    const total = parseFloat($('#total').text().replace('R$ ', '').replace(',', '.')) || 0;
    const installmentsTotal = installmentsData.reduce((sum, installment) => sum + installment.amount, 0);
    const difference = Math.abs(total - installmentsTotal);
    
    if (difference > 0.01) {
        Swal.fire({
            title: 'Inconsistência Detectada!',
            html: `
                <div class="text-start">
                    <p><strong>Total da Venda:</strong> R$ ${total.toFixed(2).replace('.', ',')}</p>
                    <p><strong>Total das Parcelas:</strong> R$ ${installmentsTotal.toFixed(2).replace('.', ',')}</p>
                    <p><strong>Diferença:</strong> R$ ${difference.toFixed(2).replace('.', ',')}</p>
                    <hr>
                    <p class="text-warning"><i class="fas fa-exclamation-triangle"></i> Os valores não conferem!</p>
                </div>
            `,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sim, continuar',
            cancelButtonText: 'Recalcular',
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d'
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
    // Auto-fill sale date with today's date if empty
    if (!$('#sale_date').val()) {
        $('#sale_date').val(new Date().toISOString().split('T')[0]);
    }
    
    // Auto-fill due date with next month if empty
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

function ensureInstallmentsFieldCorrect() {
    // This function is no longer needed as data is loaded in the main initialization
    // Keeping it empty to avoid breaking any existing calls
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
            $('#discount').val(0);
            
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
    Swal.fire({
        title: 'Funcionalidade em Desenvolvimento',
        text: 'Funcionalidade de rascunho será implementada em breve!',
        icon: 'info',
        confirmButtonText: 'OK'
    });
}

function showLoading() {
    Swal.fire({
        title: 'Salvando...',
        text: 'Aguarde enquanto atualizamos a venda.',
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}
</script>
@endsection 