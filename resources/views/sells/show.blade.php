@extends('layouts.app')

@section('title', 'Detalhes da Venda - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card fade-in">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="fas fa-receipt me-2"></i>
                        Venda #{{ $sell->id }}
                    </h4>
                    <div class="btn-group">
                        <a href="{{ route('sells.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left me-1"></i>
                            Voltar
                        </a>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- Sale Information -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-info-circle me-2"></i>
                            Informações da Venda
                        </h5>
                        <table class="table table-borderless">
                            <tr>
                                <td><strong>Data da Venda:</strong></td>
                                <td>{{ $sell->sale_date->format('d/m/Y') }}</td>
                            </tr>
                            <tr>
                                <td><strong>Forma de Pagamento:</strong></td>
                                <td>{{ $sell->payment_method_text }}</td>
                            </tr>
                            <tr>
                                <td><strong>Status:</strong></td>
                                <td>
                                    <span class="badge {{ $sell->status_badge_class }}">
                                        {{ $sell->status_text }}
                                    </span>
                                </td>
                            </tr>
                            <tr>
                                <td><strong>Status do Pagamento:</strong></td>
                                <td>
                                    <span class="badge {{ $sell->payment_status_badge_class }}">
                                        {{ $sell->payment_status_text }}
                                    </span>
                                </td>
                            </tr>
                            @if($sell->due_date)
                            <tr>
                                <td><strong>Data de Vencimento:</strong></td>
                                <td>{{ $sell->due_date->format('d/m/Y') }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5 class="mb-3">
                            <i class="fas fa-user me-2"></i>
                            Cliente
                        </h5>
                        <div class="card bg-light">
                            <div class="card-body">
                                <h6 class="card-title">{{ $sell->client->name }}</h6>
                                <p class="card-text mb-1">
                                    <i class="fas fa-envelope me-1"></i>
                                    {{ $sell->client->email }}
                                </p>
                                @if($sell->client->phone)
                                <p class="card-text mb-1">
                                    <i class="fas fa-phone me-1"></i>
                                    {{ $sell->client->phone }}
                                </p>
                                @endif
                                @if($sell->client->document)
                                <p class="card-text mb-0">
                                    <i class="fas fa-id-card me-1"></i>
                                    {{ $sell->client->document }}
                                </p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-shopping-cart me-2"></i>
                        Produtos
                    </h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Produto</th>
                                    <th width="100">Quantidade</th>
                                    <th width="120">Preço Unit.</th>
                                    <th width="120">Total</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($sell->sellItems as $item)
                                <tr>
                                    <td>
                                        <div>
                                            <strong>{{ $item->product_name }}</strong>
                                            @if($item->description)
                                                <br><small class="text-muted">{{ $item->description }}</small>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="text-center">{{ $item->quantity }}</td>
                                    <td class="text-end">R$ {{ number_format($item->unit_price, 2, ',', '.') }}</td>
                                    <td class="text-end">
                                        <strong class="text-success">R$ {{ number_format($item->total_price, 2, ',', '.') }}</strong>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Notes -->
                @if($sell->notes)
                <div class="mb-4">
                    <h5 class="mb-3">
                        <i class="fas fa-sticky-note me-2"></i>
                        Observações
                    </h5>
                    <div class="card bg-light">
                        <div class="card-body">
                            {{ $sell->notes }}
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Installments Section -->
        @if($sell->payment_method === 'cartao_credito' && $sell->installments()->count() > 0)
        <div class="card fade-in mt-4">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="fas fa-credit-card me-2"></i>
                    Parcelas
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Parcela</th>
                                <th>Valor</th>
                                <th>Vencimento</th>
                                <th>Status</th>
                                <th>Dias</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sell->installments()->orderBy('installment_number')->get() as $installment)
                            <tr>
                                <td>
                                    <strong>{{ $installment->formatted_installment_number }}</strong>
                                </td>
                                <td>
                                    <span class="text-success fw-bold">
                                        {{ $installment->formatted_amount }}
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ $installment->formatted_due_date }}</strong>
                                        @if($installment->isOverdue())
                                            <br><small class="text-danger">Vencida há {{ $installment->overdue_days }} dias</small>
                                        @elseif($installment->isDueToday())
                                            <br><small class="text-warning">Vence hoje!</small>
                                        @elseif($installment->days_until_due > 0)
                                            <br><small class="text-muted">Faltam {{ $installment->days_until_due }} dias</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <span class="badge {{ $installment->status_badge_class }}">
                                        {{ $installment->status_text }}
                                    </span>
                                </td>
                                <td>
                                    @if($installment->status === 'pending')
                                        @if($installment->isOverdue())
                                            <span class="text-danger">-{{ $installment->overdue_days }}</span>
                                        @elseif($installment->isDueToday())
                                            <span class="text-warning">0</span>
                                        @else
                                            <span class="text-muted">{{ $installment->days_until_due }}</span>
                                        @endif
                                    @else
                                        <span class="text-success">✓</span>
                                    @endif
                                </td>
                                <td>
                                    @if($installment->status === 'pending')
                                    <button type="button" 
                                            class="btn btn-sm btn-outline-success" 
                                            title="Marcar como Pago"
                                            onclick="markInstallmentAsPaid({{ $installment->id }}, '{{ $installment->formatted_installment_number }}')">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    @else
                                    <span class="text-muted">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        @endif
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
                    <span>R$ {{ number_format($sell->subtotal, 2, ',', '.') }}</span>
                </div>
                @if($sell->discount > 0)
                <div class="summary-item">
                    <span>Desconto:</span>
                    <span class="text-danger">-R$ {{ number_format($sell->discount, 2, ',', '.') }}</span>
                </div>
                @endif
                <hr>
                <div class="summary-item total">
                    <span>Total:</span>
                    <span>R$ {{ number_format($sell->total_amount, 2, ',', '.') }}</span>
                </div>

                <!-- Installments Info -->
                @if($sell->payment_method === 'cartao_credito' && $sell->installments()->count() > 0)
                <hr>
                <div class="summary-item">
                    <span>Parcelas:</span>
                    <span>{{ $sell->total_installments }}x</span>
                </div>
                <div class="summary-item">
                    <span>Valor da Parcela:</span>
                    <span>R$ {{ number_format($sell->total_amount / $sell->total_installments, 2, ',', '.') }}</span>
                </div>
                <div class="summary-item">
                    <span>Parcelas Pagas:</span>
                    <span>{{ $sell->paid_installments }}/{{ $sell->total_installments }}</span>
                </div>
                @endif

                <div class="mt-3">
                    <div class="d-grid gap-2">
                        <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete()">
                            <i class="fas fa-trash me-2"></i>
                            Excluir Venda
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Form -->
<form id="deleteForm" action="{{ route('sells.destroy', $sell->id) }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>
@endsection

@section('styles')
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
    
    .table th {
        background-color: #f8f9fa;
        border-color: #dee2e6;
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
        text: 'Tem certeza que deseja excluir esta venda? Esta ação não pode ser desfeita.',
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

function markInstallmentAsPaid(installmentId, installmentName) {
    Swal.fire({
        title: 'Marcar como Pago',
        text: `Tem certeza que deseja marcar ${installmentName} como paga?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, marcar como paga!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            markInstallmentAsPaidAction(installmentId);
        }
    });
}

function markInstallmentAsPaidAction(installmentId) {
    showLoading();
    
    $.ajax({
        url: `/installments/${installmentId}/mark-as-paid`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Pago!',
                text: 'Parcela marcada como paga com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao marcar parcela como paga.';
            
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