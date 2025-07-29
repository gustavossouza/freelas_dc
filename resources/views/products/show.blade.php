@extends('layouts.app')

@section('title', 'Detalhes do Produto - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-box me-2"></i>
                    Detalhes do Produto
                </h4>
                <div class="btn-group" role="group">
                    <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-2"></i>
                        Editar Produto
                    </a>
                    <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                        <i class="fas fa-trash me-2"></i>
                        Excluir Produto
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Product Information -->
                <div class="row">
                    <div class="col-md-8">
                        <h5 class="text-primary mb-3">{{ $product->name }}</h5>
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">ID do Produto:</label>
                                    <p class="mb-2"><strong>#{{ $product->id }}</strong></p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Status:</label>
                                    <p class="mb-2">
                                        @if($product->is_active)
                                            <span class="badge bg-success">Ativo</span>
                                        @else
                                            <span class="badge bg-danger">Inativo</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Preço:</label>
                                    <p class="mb-2">
                                        <strong class="text-success fs-5">{{ $product->formatted_price }}</strong>
                                    </p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Unidade:</label>
                                    <p class="mb-2"><strong>{{ $product->unit }}</strong></p>
                                </div>
                            </div>
                        </div>

                        @if($product->description)
                        <div class="info-item mb-4">
                            <label class="text-muted small">Descrição:</label>
                            <p class="mb-2">{{ $product->description }}</p>
                        </div>
                        @endif

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Data de Criação:</label>
                                    <p class="mb-2">{{ $product->created_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-item">
                                    <label class="text-muted small">Última Atualização:</label>
                                    <p class="mb-2">{{ $product->updated_at->format('d/m/Y H:i') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-4">
                        <div class="text-center">
                            <div class="product-avatar mb-3">
                                <div class="avatar-initial rounded-circle bg-primary" style="width: 120px; height: 120px; font-size: 3rem;">
                                    <i class="fas fa-box"></i>
                                </div>
                            </div>
                            <h6 class="text-muted">{{ $product->name }}</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sales History -->
        @if($product->sellItems->count() > 0)
        <div class="card fade-in mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-history me-2"></i>
                    Histórico de Vendas
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Venda #</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Qtd</th>
                                <th>Preço Unit.</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->sellItems as $item)
                            <tr>
                                <td>
                                    <a href="{{ route('sells.show', $item->sell->id) }}" class="text-decoration-none">
                                        #{{ $item->sell->id }}
                                    </a>
                                </td>
                                <td>{{ $item->sell->client->name ?? 'Cliente não encontrado' }}</td>
                                <td>{{ $item->sell->sale_date->format('d/m/Y') }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                <td class="text-success">R$ {{ number_format($item->total_price, 2, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Resumo
                </h5>
            </div>
            <div class="card-body">
                <div class="summary-item">
                    <span>Total de Vendas:</span>
                    <span class="badge bg-primary">{{ $product->sellItems->count() }}</span>
                </div>
                
                <div class="summary-item">
                    <span>Quantidade Total Vendida:</span>
                    <span class="badge bg-info">{{ $product->sellItems->sum('quantity') }}</span>
                </div>
                
                <div class="summary-item">
                    <span>Valor Total Vendido:</span>
                    <span class="text-success">R$ {{ number_format($product->sellItems->sum('total_price'), 2, ',', '.') }}</span>
                </div>

                <hr>
                
                <div class="summary-item">
                    <span>Preço Atual:</span>
                    <span class="text-success">{{ $product->formatted_price }}</span>
                </div>
                
                <div class="summary-item">
                    <span>Unidade:</span>
                    <span>{{ $product->unit }}</span>
                </div>

                <hr>
                
                <div class="mt-3">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-2"></i>
                            Editar Produto
                        </a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            Excluir Produto
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('products.destroy', $product->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .info-item {
        margin-bottom: 1rem;
    }
    
    .info-item label {
        font-weight: 500;
        margin-bottom: 0.25rem;
    }
    
    .summary-item {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }
    
    .summary-item:last-child {
        margin-bottom: 0;
    }
    
    .product-avatar {
        display: flex;
        justify-content: center;
        align-items: center;
    }
    
    .avatar-initial {
        display: flex;
        justify-content: center;
        align-items: center;
        color: white;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function confirmDelete() {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja excluir o produto "${@json($product->name)}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('deleteForm').submit();
        }
    });
}
</script>
@endsection 