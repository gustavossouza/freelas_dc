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
                                        <select class="form-select @error('payment_method') is-invalid @enderror" 
                                                id="payment_method" 
                                                name="payment_method">
                                            <option value="">Selecione a forma de pagamento</option>
                                            <option value="pix" {{ old('payment_method') == 'pix' ? 'selected' : '' }}>PIX</option>
                                            <option value="cartao_debito" {{ old('payment_method') == 'cartao_debito' ? 'selected' : '' }}>Cartão de Débito</option>
                                            <option value="cartao_credito" {{ old('payment_method') == 'cartao_credito' ? 'selected' : '' }}>Cartão de Crédito (até 12x)</option>
                                            <option value="boleto" {{ old('payment_method') == 'boleto' ? 'selected' : '' }}>Boleto</option>
                                        </select>
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


@endsection

@section('styles')
<!-- Select2 CSS -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
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
                console.log('Search params:', params);
                return {
                    search: params.term || '',
                    page: params.page || 1
                };
            },
            processResults: function(data, params) {
                console.log('Products data:', data);
                params.page = params.page || 1;
                
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
            error: function(xhr, status, error) {
                console.error('Select2 AJAX error:', error);
                console.error('Response:', xhr.responseText);
            },
            cache: true
        },
        minimumInputLength: 1,
        templateResult: formatProductOption,
        templateSelection: formatProductSelection
    });
    
    // Load products for reference
    loadProducts();
    
    // Test Select2 initialization
    console.log('Select2 initialized:', $('#product_id').length > 0);
    
    // Test AJAX call
    $.ajax({
        url: '{{ route("products.getProducts") }}',
        method: 'GET',
        data: { search: 'test' },
        success: function(response) {
            console.log('AJAX test successful:', response);
        },
        error: function(xhr, status, error) {
            console.error('AJAX test failed:', error);
        }
    });
    
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
        }
    });
    
    // Installments change
    $('#installments').on('change', function() {
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
        
        showLoading();
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
            console.error('Erro ao carregar produtos');
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
    } else {
        installmentsSummary.hide();
    }
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