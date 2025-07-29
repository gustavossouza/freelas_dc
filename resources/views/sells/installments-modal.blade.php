<div class="installments-modal-content">
    <!-- Sale Info -->
    <div class="row mb-4">
        <div class="col-md-6">
            <h6 class="text-primary">
                <i class="fas fa-receipt me-2"></i>
                Venda #{{ $sell->id }}
            </h6>
            <p class="mb-1">
                <strong>Cliente:</strong> {{ $sell->client->name }}
            </p>
            <p class="mb-1">
                <strong>Data:</strong> {{ $sell->sale_date->format('d/m/Y') }}
            </p>
            <p class="mb-0">
                <strong>Total:</strong> <span class="text-success">R$ {{ number_format($sell->total_amount, 2, ',', '.') }}</span>
            </p>
        </div>
        <div class="col-md-6 text-end">
            <div class="installment-summary">
                <h6 class="text-info">
                    <i class="fas fa-chart-pie me-2"></i>
                    Resumo das Parcelas
                </h6>
                <p class="mb-1">
                    <strong>Total:</strong> {{ $sell->total_installments }}x
                </p>
                <p class="mb-1">
                    <strong>Pagas:</strong> <span class="text-success">{{ $sell->paid_installments }}</span>
                </p>
                <p class="mb-1">
                    <strong>Pendentes:</strong> <span class="text-warning">{{ $sell->pending_installments }}</span>
                </p>
                @if($sell->overdue_installments > 0)
                <p class="mb-0">
                    <strong>Vencidas:</strong> <span class="text-danger">{{ $sell->overdue_installments }}</span>
                </p>
                @endif
            </div>
        </div>
    </div>

    <!-- Installments Table -->
    <div class="table-responsive">
        <table class="table table-hover">
            <thead class="table-light">
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
                @forelse($sell->installments as $installment)
                <tr class="{{ $installment->isOverdue() ? 'table-danger' : ($installment->isDueToday() ? 'table-warning' : '') }}">
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
                                onclick="markInstallmentAsPaidModal({{ $installment->id }}, '{{ $installment->formatted_installment_number }}')">
                            <i class="fas fa-check"></i>
                        </button>
                        @else
                        <span class="text-muted">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="text-center py-4">
                        <div class="empty-state">
                            <i class="fas fa-credit-card fa-2x text-muted mb-3"></i>
                            <h6>Nenhuma parcela encontrada</h6>
                            <p class="text-muted">Esta venda não possui parcelas.</p>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Quick Actions -->
    @if($sell->pending_installments > 0)
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h6 class="text-warning mb-0">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Ações Rápidas
                </h6>
            </div>
            <div>
                <button type="button" 
                        class="btn btn-warning btn-sm me-2"
                        onclick="markAllPendingAsPaid({{ $sell->id }})">
                    <i class="fas fa-check-double me-2"></i>
                    Marcar Todas como Pagas
                </button>
                @if($sell->overdue_installments > 0)
                <button type="button" 
                        class="btn btn-danger btn-sm"
                        onclick="markOverdueAsPaid({{ $sell->id }})">
                    <i class="fas fa-exclamation me-2"></i>
                    Pagar Vencidas
                </button>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.installments-modal-content {
    padding: 0;
}

.installment-summary {
    background: #f8f9fa;
    padding: 1rem;
    border-radius: 8px;
    border-left: 4px solid #17a2b8;
}

.empty-state {
    text-align: center;
    padding: 2rem 1rem;
}

.table tbody tr.table-danger {
    background-color: rgba(220, 53, 69, 0.1);
}

.table tbody tr.table-warning {
    background-color: rgba(255, 193, 7, 0.1);
}
</style>

<script>
function markAllPendingAsPaid(sellId) {
    Swal.fire({
        title: 'Marcar Todas como Pagas',
        text: 'Tem certeza que deseja marcar todas as parcelas pendentes como pagas?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, marcar todas!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            markAllPendingAsPaidAction(sellId);
        }
    });
}

function markAllPendingAsPaidAction(sellId) {
    showLoading();
    
    $.ajax({
        url: `/sells/${sellId}/mark-all-pending-as-paid`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Pagas!',
                text: 'Todas as parcelas foram marcadas como pagas.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload the modal content
                openInstallmentsModal(sellId, $('#installmentsModalLabel').text().replace('Parcelas - ', ''));
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao marcar parcelas como pagas.';
            
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

function markOverdueAsPaid(sellId) {
    Swal.fire({
        title: 'Pagar Parcelas Vencidas',
        text: 'Tem certeza que deseja marcar todas as parcelas vencidas como pagas?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, pagar vencidas!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            markOverdueAsPaidAction(sellId);
        }
    });
}

function markOverdueAsPaidAction(sellId) {
    showLoading();
    
    $.ajax({
        url: `/sells/${sellId}/mark-overdue-as-paid`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Pagas!',
                text: 'Todas as parcelas vencidas foram marcadas como pagas.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                // Reload the modal content
                openInstallmentsModal(sellId, $('#installmentsModalLabel').text().replace('Parcelas - ', ''));
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao marcar parcelas vencidas como pagas.';
            
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