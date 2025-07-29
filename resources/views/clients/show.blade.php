@extends('layouts.app')

@section('title', 'Detalhes do Cliente - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Client Information Card -->
        <div class="card fade-in mb-4">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-user me-2"></i>
                        Detalhes do Cliente
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('clients.edit', $client->id) }}" class="btn btn-warning btn-sm">
                            <i class="fas fa-edit me-1"></i>
                            Editar
                        </a>
                        <button type="button" 
                                class="btn btn-danger btn-sm" 
                                onclick="confirmDeleteClient({{ $client->id }}, '{{ $client->name }}')">
                            <i class="fas fa-trash me-1"></i>
                            Excluir
                        </button>
                        <a href="{{ route('clients.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Client Avatar and Basic Info -->
                    <div class="col-md-4 text-center mb-4">
                        <div class="client-avatar-large mx-auto mb-3">
                            <div class="avatar-placeholder-large">
                                {{ strtoupper(substr($client->name, 0, 2)) }}
                            </div>
                        </div>
                        <h5 class="mb-1">{{ $client->name }}</h5>
                        @if($client->document)
                            <p class="text-muted mb-2">{{ $client->formatted_document }}</p>
                        @endif
                        <div class="client-status">
                            <span class="badge bg-success">Cliente Ativo</span>
                        </div>
                    </div>
                    
                    <!-- Contact Information -->
                    <div class="col-md-8">
                        <div class="row">
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-address-card me-2"></i>
                                    Informações de Contato
                                </h6>
                                <div class="mb-3">
                                    <label class="form-label text-muted">E-mail</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-envelope text-primary me-2"></i>
                                        <a href="mailto:{{ $client->email }}" class="text-decoration-none">
                                            {{ $client->email }}
                                        </a>
                                    </div>
                                </div>
                                @if($client->phone)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Telefone</label>
                                    <div class="d-flex align-items-center">
                                        <i class="fas fa-phone text-primary me-2"></i>
                                        <a href="tel:{{ $client->phone }}" class="text-decoration-none">
                                            {{ $client->formatted_phone }}
                                        </a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            
                            <div class="col-md-6">
                                <h6 class="text-primary mb-3">
                                    <i class="fas fa-map-marker-alt me-2"></i>
                                    Endereço
                                </h6>
                                @if($client->address || $client->city || $client->state)
                                <div class="mb-3">
                                    <label class="form-label text-muted">Endereço Completo</label>
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-home text-primary me-2 mt-1"></i>
                                        <div>
                                            @if($client->address)
                                                <div>{{ $client->address }}</div>
                                            @endif
                                            @if($client->city || $client->state)
                                                <div class="text-muted">
                                                    {{ $client->city }}{{ $client->city && $client->state ? ', ' : '' }}{{ $client->state }}
                                                </div>
                                            @endif
                                            @if($client->zip_code)
                                                <div class="text-muted">{{ $client->zip_code }}</div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                @else
                                <div class="text-muted">
                                    <i class="fas fa-info-circle me-1"></i>
                                    Endereço não informado
                                </div>
                                @endif
                            </div>
                        </div>
                        
                        @if($client->notes)
                        <hr>
                        <div class="mt-3">
                            <h6 class="text-primary mb-2">
                                <i class="fas fa-sticky-note me-2"></i>
                                Observações
                            </h6>
                            <p class="mb-0">{{ $client->notes }}</p>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Statistics Sidebar -->
    <div class="col-lg-4">
        <!-- Client Statistics -->
        <div class="card fade-in mb-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-chart-bar me-2"></i>
                    Estatísticas
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-number text-primary">{{ $totalPurchases ?? 0 }}</div>
                            <div class="stat-label">Compras</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-number text-success">R$ {{ number_format($totalSpent ?? 0, 2, ',', '.') }}</div>
                            <div class="stat-label">Total Gasto</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-number text-info">R$ {{ number_format($avgPurchaseValue ?? 0, 2, ',', '.') }}</div>
                            <div class="stat-label">Ticket Médio</div>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="stat-item">
                            <div class="stat-number text-warning">{{ $pendingInstallments->count() ?? 0 }}</div>
                            <div class="stat-label">Parcelas Pendentes</div>
                        </div>
                    </div>
                </div>
                
                @if($lastPurchase)
                <hr>
                <div class="text-center">
                    <h6 class="text-muted mb-2">Última Compra</h6>
                    <div class="text-primary">{{ $lastPurchase->sale_date->format('d/m/Y') }}</div>
                    <small class="text-muted">R$ {{ number_format($lastPurchase->total_amount, 2, ',', '.') }}</small>
                </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-bolt me-2"></i>
                    Ações Rápidas
                </h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('sells.create') }}?client_id={{ $client->id }}" class="btn btn-primary">
                        <i class="fas fa-plus me-2"></i>
                        Nova Venda
                    </a>
                    <a href="mailto:{{ $client->email }}" class="btn btn-outline-primary">
                        <i class="fas fa-envelope me-2"></i>
                        Enviar E-mail
                    </a>
                    @if($client->phone)
                    <a href="tel:{{ $client->phone }}" class="btn btn-outline-success">
                        <i class="fas fa-phone me-2"></i>
                        Ligar
                    </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Sales -->
@if(isset($client->sells) && $client->sells->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Histórico de Compras
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Data</th>
                                <th>Itens</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($client->sells->take(10) as $sell)
                            <tr>
                                <td>#{{ $sell->id }}</td>
                                <td>{{ $sell->sale_date->format('d/m/Y') }}</td>
                                <td>{{ $sell->sellItems->count() }} itens</td>
                                <td>
                                    <strong class="text-success">
                                        R$ {{ number_format($sell->total_amount, 2, ',', '.') }}
                                    </strong>
                                </td>
                                <td>
                                    <span class="badge {{ $sell->status_badge_class }}">
                                        {{ $sell->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <span class="badge {{ $sell->payment_status_badge_class }}">
                                        {{ $sell->payment_status_text }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('sells.show', $sell->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                @if($client->sells->count() > 10)
                <div class="text-center mt-3">
                    <a href="{{ route('sells.index') }}?client_id={{ $client->id }}" class="btn btn-outline-primary">
                        Ver Todas as Compras
                    </a>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Pending Installments -->
@if(isset($pendingInstallments) && $pendingInstallments->count() > 0)
<div class="row mt-4">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Parcelas Pendentes
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Venda</th>
                                <th>Parcela</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pendingInstallments->take(5) as $installment)
                            <tr>
                                <td>#{{ $installment->sell->id }}</td>
                                <td>{{ $installment->formatted_installment_number }}</td>
                                <td>
                                    <strong class="text-primary">
                                        R$ {{ number_format($installment->amount, 2, ',', '.') }}
                                    </strong>
                                </td>
                                <td>{{ $installment->formatted_due_date }}</td>
                                <td>
                                    <span class="badge {{ $installment->status_badge_class }}">
                                        {{ $installment->status_text }}
                                    </span>
                                </td>
                                <td>
                                    <button class="btn btn-sm btn-success" 
                                            onclick="markAsPaid({{ $installment->id }})">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endif
@endsection

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<style>
    .client-avatar-large {
        width: 80px;
        height: 80px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-placeholder-large {
        width: 80px;
        height: 80px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 1.5rem;
    }
    
    .stat-item {
        padding: 1rem;
        border-radius: 12px;
        background: rgba(99, 102, 241, 0.05);
    }
    
    .stat-number {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        font-size: 0.875rem;
        color: #6c757d;
        font-weight: 500;
    }
    
    .form-label {
        font-size: 0.875rem;
        font-weight: 500;
        margin-bottom: 0.5rem;
    }
    
    .table tbody tr {
        cursor: pointer;
    }
    
    .table tbody tr:hover {
        background-color: rgba(99, 102, 241, 0.05);
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script>
function markAsPaid(installmentId) {
    if (confirm('Confirmar pagamento desta parcela?')) {
        showLoading();
        
        $.ajax({
            url: `/installments/${installmentId}/mark-as-paid`,
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                hideLoading();
                location.reload();
            },
            error: function(xhr) {
                hideLoading();
                alert('Erro ao marcar parcela como paga.');
            }
        });
    }
}

function confirmDeleteClient(clientId, clientName) {
    // Verificar se o cliente tem vendas associadas
    $.ajax({
        url: `/clients/${clientId}/check-sales`,
        method: 'GET',
        success: function(response) {
            if (response.hasSales) {
                Swal.fire({
                    title: 'Não é possível excluir',
                    text: `O cliente "${clientName}" possui ${response.salesCount} venda(s) associada(s) e não pode ser excluído.`,
                    icon: 'warning',
                    confirmButtonText: 'Entendi',
                    confirmButtonColor: '#3085d6'
                });
            } else {
                // Confirmar exclusão
                Swal.fire({
                    title: 'Confirmar exclusão',
                    text: `Tem certeza que deseja excluir o cliente "${clientName}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sim, excluir!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        deleteClient(clientId);
                    }
                });
            }
        },
        error: function() {
            // Se não conseguir verificar, mostrar confirmação normal
            Swal.fire({
                title: 'Confirmar exclusão',
                text: `Tem certeza que deseja excluir o cliente "${clientName}"?`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sim, excluir!',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    deleteClient(clientId);
                }
            });
        }
    });
}

function deleteClient(clientId) {
    showLoading();
    
    $.ajax({
        url: `/clients/${clientId}`,
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Excluído!',
                text: 'Cliente excluído com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                window.location.href = '{{ route("clients.index") }}';
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao excluir cliente.';
            
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