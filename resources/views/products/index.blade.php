@extends('layouts.app')

@section('title', 'Produtos - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Produtos
                </h4>
                <a href="{{ route('products.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Produto
                </a>
            </div>
            <div class="card-body">
                <!-- Stats Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-primary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Total de Produtos</h6>
                                        <h3 class="mb-0">{{ $totalProducts ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-box fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-success text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Ativos</h6>
                                        <h3 class="mb-0">{{ $activeProducts ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar produtos...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos os Status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                        </select>
                    </div>

                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="exportProducts()">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="productsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Produto</th>
                                <th>Preço</th>
                                <th>Unidade</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($products ?? [] as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="product-placeholder me-3">
                                            <i class="fas fa-box"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $product->name }}</h6>
                                            @if($product->description)
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-success">{{ $product->formatted_price }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $product->unit }}</strong>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($product->is_active)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-secondary">Inativo</span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('products.show', $product->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('products.edit', $product->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir"
                                                onclick="confirmDeleteProduct({{ $product->id }}, '{{ $product->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-box fa-3x text-muted mb-3"></i>
                                        <h5>Nenhum produto encontrado</h5>
                                        <p class="text-muted">Comece adicionando seu primeiro produto.</p>
                                        <a href="{{ route('products.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Adicionar Produto
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($products) && $products->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .product-image {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .product-placeholder {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-size: 1rem;
    }
    
    .empty-state {
        text-align: center;
        padding: 2rem;
    }
    
    .btn-group .btn {
        margin-right: 0.25rem;
    }
    
    .btn-group .btn:last-child {
        margin-right: 0;
    }
    
    .table tbody tr {
        cursor: pointer;
    }
    
    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
    
    .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-danger {
        border: none;
        border-radius: 12px;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#productsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Status filter
    $('#statusFilter').on('change', function() {
        filterTable();
    });
    
    // Stock filter
    $('#stockFilter').on('change', function() {
        filterTable();
    });
    
    // Row click to view details
    $('#productsTable tbody tr').on('click', function(e) {
        if (!$(e.target).closest('.btn-group').length) {
            const productId = $(this).find('td:first').text();
            window.location.href = `/products/${productId}`;
        }
    });
    
    // Tooltip initialization
    $('[title]').tooltip();
});

function filterTable() {
    const status = $('#statusFilter').val();
    const stock = $('#stockFilter').val();
    
    $('#productsTable tbody tr').each(function() {
        const row = $(this);
        const statusText = row.find('td:nth-child(6) .badge').first().text().toLowerCase();
        const stockText = row.find('td:nth-child(6) .badge').last().text().toLowerCase();
        
        let showRow = true;
        
        if (status) {
            if (status === 'active' && !statusText.includes('ativo')) {
                showRow = false;
            } else if (status === 'inactive' && !statusText.includes('inativo')) {
                showRow = false;
            }
        }
        
        row.toggle(showRow);
    });
}

function exportProducts() {
    showLoading();
    // TODO: Implement export functionality
    setTimeout(() => {
        hideLoading();
        alert('Funcionalidade de exportação será implementada em breve!');
    }, 1000);
}

function confirmDeleteProduct(productId, productName) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja excluir o produto "${productName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteProduct(productId);
        }
    });
}

function deleteProduct(productId) {
    showLoading();
    
    $.ajax({
        url: `/products/${productId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Excluído!',
                text: 'Produto excluído com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao excluir produto.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            }
            
            Swal.fire({
                title: 'Erro!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
</script>
@endsection 