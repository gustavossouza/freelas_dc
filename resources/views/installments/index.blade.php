@extends('layouts.app')

@section('title', 'Parcelas - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>
                    Parcelas
                </h4>
                <a href="{{ route('installments.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nova Parcela
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
                                        <h6 class="card-title">Total de Parcelas</h6>
                                        <h3 class="mb-0">{{ $totalInstallments ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
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
                                        <h6 class="card-title">Pagas</h6>
                                        <h3 class="mb-0">{{ $paidInstallments ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-check-circle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-warning text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Pendentes</h6>
                                        <h3 class="mb-0">{{ $pendingInstallments ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Vencidas</h6>
                                        <h3 class="mb-0">{{ $overdueInstallments ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar parcelas...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos os Status</option>
                            <option value="pending">Pendente</option>
                            <option value="paid">Paga</option>
                            <option value="overdue">Vencida</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <input type="date" class="form-control" id="dateFilter" placeholder="Filtrar por data">
                    </div>
                    <div class="col-md-2">
                        <button class="btn btn-outline-secondary w-100" onclick="exportInstallments()">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <!-- Installments Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="installmentsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Venda</th>
                                <th>Cliente</th>
                                <th>Parcela</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($installments ?? [] as $installment)
                            <tr>
                                <td>{{ $installment->id }}</td>
                                <td>
                                    <a href="{{ route('sells.show', $installment->sell_id) }}" class="text-decoration-none">
                                        #{{ $installment->sell_id }}
                                    </a>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-initial rounded-circle bg-primary">
                                                {{ strtoupper(substr($installment->sell->client->name ?? 'N/A', 0, 2)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $installment->sell->client->name ?? 'Cliente não encontrado' }}</h6>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $installment->installment_number }}/{{ $installment->sell->installments_count ?? 1 }}</span>
                                </td>
                                <td>
                                    <strong class="text-success">R$ {{ number_format($installment->amount, 2, ',', '.') }}</strong>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($installment->due_date)->format('d/m/Y') }}</strong>
                                        @if($installment->status == 'overdue')
                                            <br>
                                            <small class="text-danger">
                                                Vencida há {{ \Carbon\Carbon::parse($installment->due_date)->diffForHumans() }}
                                            </small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    @if($installment->status == 'pending')
                                        <span class="badge bg-warning">Pendente</span>
                                    @elseif($installment->status == 'paid')
                                        <span class="badge bg-success">Paga</span>
                                    @else
                                        <span class="badge bg-danger">Vencida</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('installments.show', $installment->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('installments.edit', $installment->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($installment->status == 'pending')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                title="Marcar como Paga"
                                                onclick="markAsPaid({{ $installment->id }})">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir"
                                                onclick="confirmDelete('{{ route('installments.destroy', $installment->id) }}', 'Tem certeza que deseja excluir esta parcela?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                                        <h5>Nenhuma parcela encontrada</h5>
                                        <p class="text-muted">Comece criando sua primeira parcela.</p>
                                        <a href="{{ route('installments.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Nova Parcela
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($installments) && $installments->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $installments->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .avatar-sm {
        width: 40px;
        height: 40px;
    }
    
    .avatar-initial {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: 600;
        font-size: 0.875rem;
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
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#installmentsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // Status filter
    $('#statusFilter').on('change', function() {
        filterTable();
    });
    
    // Date filter
    $('#dateFilter').on('change', function() {
        filterTable();
    });
    
    // Row click to view details
    $('#installmentsTable tbody tr').on('click', function(e) {
        if (!$(e.target).closest('.btn-group').length) {
            const installmentId = $(this).find('td:first').text();
            window.location.href = `/installments/${installmentId}`;
        }
    });
    
    // Tooltip initialization
    $('[title]').tooltip();
});

function filterTable() {
    const status = $('#statusFilter').val();
    const date = $('#dateFilter').val();
    
    $('#installmentsTable tbody tr').each(function() {
        const row = $(this);
        const statusText = row.find('td:nth-child(7) .badge').text().toLowerCase();
        const dateText = row.find('td:nth-child(6) strong').text();
        
        let showRow = true;
        
        if (status && !statusText.includes(status)) {
            showRow = false;
        }
        
        if (date && dateText !== date) {
            showRow = false;
        }
        
        row.toggle(showRow);
    });
}

function markAsPaid(installmentId) {
    if (confirm('Tem certeza que deseja marcar esta parcela como paga?')) {
        showLoading();
        
        $.ajax({
            url: `/installments/${installmentId}/mark-as-paid`,
            method: 'POST',
            success: function(response) {
                hideLoading();
                location.reload();
            },
            error: function() {
                hideLoading();
                alert('Erro ao marcar parcela como paga!');
            }
        });
    }
}

function exportInstallments() {
    showLoading();
    // TODO: Implement export functionality
    setTimeout(() => {
        hideLoading();
        alert('Funcionalidade de exportação será implementada em breve!');
    }, 1000);
}
</script>
@endsection 