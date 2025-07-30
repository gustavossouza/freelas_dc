@extends('layouts.app')

@section('title', 'Vendas - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-shopping-cart me-2"></i>
                    Vendas
                </h4>
                <a href="{{ route('sells.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Nova Venda
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
                                        <h6 class="card-title">Total de Vendas</h6>
                                        <h3 class="mb-0">{{ $totalSells ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x"></i>
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
                                        <h6 class="card-title">Vendas Hoje</h6>
                                        <h3 class="mb-0">{{ $todaySells ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-day fa-2x"></i>
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
                                        <h6 class="card-title">Valor Total</h6>
                                        <h3 class="mb-0">R$ {{ number_format($totalValue ?? 0, 2, ',', '.') }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-info text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Pendentes</h6>
                                        <h3 class="mb-0">{{ $pendingSells ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-clock fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Filters -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h6 class="mb-0">
                            <i class="fas fa-filter me-2"></i>
                            Filtros Avançados
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <!-- Search -->
                            <div class="col-md-3">
                                <label for="searchInput" class="form-label">Buscar</label>
                                <div class="input-group">
                                    <span class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </span>
                                    <input type="text" id="searchInput" class="form-control" placeholder="Cliente, ID da venda...">
                                </div>
                            </div>

                            <!-- Payment Status -->
                            <div class="col-md-2">
                                <label for="paymentFilter" class="form-label">Status Pagamento</label>
                                <select class="form-select" id="paymentFilter">
                                    <option value="">Todos</option>
                                    <option value="pending">Pendente</option>
                                    <option value="paid">Pago</option>
                                    <option value="partial">Parcial</option>
                                    <option value="refunded">Reembolsado</option>
                                </select>
                            </div>

                            <!-- Payment Method -->
                            <div class="col-md-2">
                                <label for="paymentMethodFilter" class="form-label">Forma Pagamento</label>
                                <select class="form-select" id="paymentMethodFilter">
                                    <option value="">Todas</option>
                                    <option value="pix">PIX</option>
                                    <option value="cartao_debito">Cartão Débito</option>
                                    <option value="cartao_credito">Cartão Crédito</option>
                                    <option value="boleto">Boleto</option>
                                </select>
                            </div>

                            


                        </div>

                            <!-- Installments Filter -->
                            <div class="col-md-2">
                                <label for="installmentsFilter" class="form-label">Parcelas</label>
                                <select class="form-select" id="installmentsFilter">
                                    <option value="">Todas</option>
                                    <option value="with_installments">Com Parcelas</option>
                                    <option value="without_installments">Sem Parcelas</option>
                                    <option value="overdue_installments">Com Vencidas</option>
                                </select>
                            </div>

                            <!-- Actions -->
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-outline-secondary btn-sm" onclick="clearFilters()" title="Limpar Filtros">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-success btn-sm" onclick="exportSells()" title="Exportar">
                                        <i class="fas fa-download"></i>
                                    </button>
                                </div>
                            </div>

                            <!-- Results Counter -->
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <div class="text-end">
                                    <span class="badge bg-info" id="resultsCounter">
                                        {{ $sells->total() ?? 0 }} vendas encontradas
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Sells Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="sellsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Data</th>
                                <th>Valor Total</th>
                                <th>Forma Pagamento</th>
                                <th>Parcelas</th>
                                <th>Pagamento</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($sells ?? [] as $sell)
                            <tr>
                                <td>#{{ $sell->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-sm me-3">
                                            <div class="avatar-initial rounded-circle bg-primary">
                                                {{ strtoupper(substr($sell->client->name ?? 'N/A', 0, 2)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $sell->client->name ?? 'Cliente não encontrado' }}</h6>
                                            <small class="text-muted">{{ $sell->client->email ?? '' }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong>{{ \Carbon\Carbon::parse($sell->sale_date)->format('d/m/Y') }}</strong>
                                        <br>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($sell->sale_date)->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <strong class="text-success">R$ {{ number_format($sell->total_amount, 2, ',', '.') }}</strong>
                                        @if($sell->discount > 0)
                                            <br>
                                            <small class="text-muted">Desc: R$ {{ number_format($sell->discount, 2, ',', '.') }}</small>
                                        @endif
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-secondary">{{ $sell->payment_method_text }}</span>
                                </td>

                                <td>
                                    @if($sell->payment_method === 'cartao_credito' && $sell->installments()->count() > 0)
                                        <div>
                                            <span class="badge bg-info">{{ $sell->total_installments }}x</span>
                                            <br>
                                            <small class="text-muted">
                                                {{ $sell->paid_installments }}/{{ $sell->total_installments }} pagas
                                            </small>
                                            @if($sell->overdue_installments > 0)
                                                <br>
                                                <small class="text-danger">
                                                    {{ $sell->overdue_installments }} vencidas
                                                </small>
                                            @endif
                                            <br>
                                            <button type="button" 
                                                    class="btn btn-sm btn-outline-primary mt-1" 
                                                    title="Gerenciar Parcelas"
                                                    onclick="openInstallmentsModal({{ $sell->id }}, '{{ $sell->client->name ?? 'Venda' }}')">
                                                <i class="fas fa-credit-card"></i>
                                            </button>
                                        </div>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td>
                                    @if($sell->payment_status == 'pending')
                                        <span class="badge bg-warning">Pendente</span>
                                    @elseif($sell->payment_status == 'paid')
                                        <span class="badge bg-success">Pago</span>
                                    @elseif($sell->payment_status == 'partial')
                                        <span class="badge bg-info">Parcial</span>
                                    @else
                                        <span class="badge bg-secondary">Reembolsado</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('sells.show', $sell->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('sells.edit', $sell->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        @if($sell->payment_status == 'pending')
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-success" 
                                                title="Aprovar Pagamento"
                                                onclick="event.stopPropagation(); approvePayment({{ $sell->id }}, '{{ $sell->client->name ?? 'Venda' }}')">
                                            <i class="fas fa-check"></i>
                                        </button>
                                        @endif
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir"
                                                onclick="event.stopPropagation(); confirmDeleteSell({{ $sell->id }}, '{{ $sell->client->name ?? 'Venda' }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="8" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-shopping-cart fa-3x text-muted mb-3"></i>
                                        <h5>Nenhuma venda encontrada</h5>
                                        <p class="text-muted">Comece criando sua primeira venda.</p>
                                        <a href="{{ route('sells.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Nova Venda
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($sells) && $sells->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $sells->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Installments Modal -->
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
                <div id="installmentsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>
<!-- Installments Modal -->
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
                <div id="installmentsContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<!-- SweetAlert2 CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<!-- DataTables CSS -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
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
    
    .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-info {
        border: none;
        border-radius: 12px;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- DataTables JS -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<!-- SheetJS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('input', function() {
        filterTable();
    });
    
    // Payment status filter
    $('#paymentFilter').on('change', function() {
        filterTable();
    });
    
    // Payment method filter
    $('#paymentMethodFilter').on('change', function() {
        filterTable();
    });
    

    

    
    // Installments filter
    $('#installmentsFilter').on('change', function() {
        filterTable();
    });
    
    // Row click to view details
    $('#sellsTable tbody tr').on('click', function(e) {
        if (!$(e.target).closest('.btn-group').length && !$(e.target).closest('button').length) {
            const sellId = $(this).find('td:first').text().replace('#', '');
            window.location.href = `/sells/${sellId}`;
        }
    });
    
    // Tooltip initialization
    $('[title]').tooltip();
});

function filterTable() {
    const search = $('#searchInput').val().toLowerCase();
    const paymentStatus = $('#paymentFilter').val();
    const paymentMethod = $('#paymentMethodFilter').val();
    const installments = $('#installmentsFilter').val();
    
    let visibleCount = 0;
    
    $('#sellsTable tbody tr').each(function() {
        const row = $(this);
        const rowText = row.text().toLowerCase();
        const paymentStatusText = row.find('td:nth-child(7) .badge').text().toLowerCase();
        const paymentMethodText = row.find('td:nth-child(5) .badge').text().toLowerCase();
        const hasInstallments = row.find('td:nth-child(6) .badge').length > 0;
        const hasOverdueInstallments = row.find('td:nth-child(6) .text-danger').length > 0;
        
        let showRow = true;
        
        // Search filter
        if (search && !rowText.includes(search)) {
            showRow = false;
        }
        
        // Payment status filter
        if (paymentStatus && !paymentStatusText.includes(paymentStatus)) {
            showRow = false;
        }
        
        // Payment method filter
        if (paymentMethod) {
            const methodMap = {
                'pix': 'pix',
                'cartao_debito': 'cartão de débito',
                'cartao_credito': 'cartão de crédito',
                'boleto': 'boleto'
            };
            const expectedText = methodMap[paymentMethod];
            if (!paymentMethodText.includes(expectedText)) {
                showRow = false;
            }
        }
        
        // Installments filter
        if (installments) {
            if (installments === 'with_installments' && !hasInstallments) {
                showRow = false;
            } else if (installments === 'without_installments' && hasInstallments) {
                showRow = false;
            } else if (installments === 'overdue_installments' && !hasOverdueInstallments) {
                showRow = false;
            }
        }
        
        row.toggle(showRow);
        if (showRow) visibleCount++;
    });
    
    // Update results counter
    $('#resultsCounter').text(`${visibleCount} vendas encontradas`);
}





function clearFilters() {
    $('#searchInput').val('');
    $('#paymentFilter').val('');
    $('#paymentMethodFilter').val('');
    $('#installmentsFilter').val('');
    filterTable();
}



function exportSells() {
    Swal.fire({
        title: 'Exportar Vendas',
        text: 'Escolha o formato de exportação:',
        icon: 'question',
        showCancelButton: true,
        showDenyButton: true,
        showConfirmButton: true,
        confirmButtonText: 'PDF',
        denyButtonText: 'Excel',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#dc3545',
        denyButtonColor: '#28a745'
    }).then((result) => {
        if (result.isConfirmed) {
            exportToPDF();
        } else if (result.isDenied) {
            exportToExcel();
        }
    });
}

function exportToPDF() {
    showLoading();
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Título
        doc.setFontSize(18);
        doc.text('Relatório de Vendas', 14, 22);
        
        // Data de geração
        doc.setFontSize(10);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')}`, 14, 32);
        
        // Filtros aplicados
        const filters = [];
        if ($('#searchInput').val()) filters.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#paymentFilter').val()) filters.push(`Status: ${$('#paymentFilter option:selected').text()}`);
        if ($('#paymentMethodFilter').val()) filters.push(`Forma: ${$('#paymentMethodFilter option:selected').text()}`);
        if ($('#installmentsFilter').val()) filters.push(`Parcelas: ${$('#installmentsFilter option:selected').text()}`);
        
        if (filters.length > 0) {
            doc.text(`Filtros: ${filters.join(', ')}`, 14, 42);
        }
        
        // Dados da tabela
        const tableData = [];
        const headers = ['ID', 'Cliente', 'Data', 'Valor', 'Forma', 'Parcelas', 'Status'];
        
        $('#sellsTable tbody tr:visible').each(function() {
            const row = [];
            const cells = $(this).find('td');
            
            // ID
            row.push($(cells[0]).text().trim());
            
            // Cliente
            row.push($(cells[1]).find('h6').text().trim());
            
            // Data
            row.push($(cells[2]).find('strong').text().trim());
            
            // Valor
            row.push($(cells[3]).find('strong').text().trim());
            
            // Forma de Pagamento
            row.push($(cells[4]).find('.badge').text().trim());
            
            // Parcelas
            const parcelasCell = $(cells[5]);
            if (parcelasCell.find('.badge').length > 0) {
                row.push(parcelasCell.find('.badge').text().trim());
            } else {
                row.push('-');
            }
            
            // Status
            row.push($(cells[6]).find('.badge').text().trim());
            
            tableData.push(row);
        });
        
        // Adicionar tabela
        doc.autoTable({
            head: [headers],
            body: tableData,
            startY: 50,
            styles: {
                fontSize: 8,
                cellPadding: 2
            },
            headStyles: {
                fillColor: [99, 102, 241],
                textColor: 255
            },
            alternateRowStyles: {
                fillColor: [248, 249, 250]
            }
        });
        
        // Salvar PDF
        const fileName = `vendas_${new Date().toISOString().split('T')[0]}.pdf`;
        doc.save(fileName);
        
        hideLoading();
        Swal.fire({
            title: 'Sucesso!',
            text: 'Relatório PDF exportado com sucesso!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        
    } catch (error) {
        hideLoading();
        console.error('Erro ao gerar PDF:', error);
        Swal.fire({
            title: 'Erro!',
            text: 'Erro ao gerar PDF. Verifique o console para mais detalhes.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

function exportToExcel() {
    showLoading();
    
    try {
        // Preparar dados
        const data = [];
        const headers = ['ID', 'Cliente', 'Email', 'Data', 'Valor Total', 'Desconto', 'Forma Pagamento', 'Parcelas', 'Status Pagamento'];
        
        $('#sellsTable tbody tr:visible').each(function() {
            const row = {};
            const cells = $(this).find('td');
            
            // ID
            row['ID'] = $(cells[0]).text().trim();
            
            // Cliente
            row['Cliente'] = $(cells[1]).find('h6').text().trim();
            
            // Email
            row['Email'] = $(cells[1]).find('small').text().trim();
            
            // Data
            row['Data'] = $(cells[2]).find('strong').text().trim();
            
            // Valor Total
            row['Valor Total'] = $(cells[3]).find('strong').text().trim();
            
            // Desconto
            const descontoText = $(cells[3]).find('small').text().trim();
            row['Desconto'] = descontoText ? descontoText.replace('Desc: ', '') : 'R$ 0,00';
            
            // Forma de Pagamento
            row['Forma Pagamento'] = $(cells[4]).find('.badge').text().trim();
            
            // Parcelas
            const parcelasCell = $(cells[5]);
            if (parcelasCell.find('.badge').length > 0) {
                row['Parcelas'] = parcelasCell.find('.badge').text().trim();
            } else {
                row['Parcelas'] = '-';
            }
            
            // Status Pagamento
            row['Status Pagamento'] = $(cells[6]).find('.badge').text().trim();
            
            data.push(row);
        });
        
        // Criar workbook
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(data);
        
        // Configurar largura das colunas
        const colWidths = [
            { wch: 8 },   // ID
            { wch: 25 },  // Cliente
            { wch: 30 },  // Email
            { wch: 12 },  // Data
            { wch: 15 },  // Valor Total
            { wch: 15 },  // Desconto
            { wch: 20 },  // Forma Pagamento
            { wch: 15 },  // Parcelas
            { wch: 15 }   // Status Pagamento
        ];
        ws['!cols'] = colWidths;
        
        // Adicionar worksheet ao workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Vendas');
        
        // Adicionar informações de filtros
        const filterInfo = [];
        if ($('#searchInput').val()) filterInfo.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#paymentFilter').val()) filterInfo.push(`Status: ${$('#paymentFilter option:selected').text()}`);
        if ($('#paymentMethodFilter').val()) filterInfo.push(`Forma: ${$('#paymentMethodFilter option:selected').text()}`);
        if ($('#installmentsFilter').val()) filterInfo.push(`Parcelas: ${$('#installmentsFilter option:selected').text()}`);
        
        if (filterInfo.length > 0) {
            const filterWs = XLSX.utils.aoa_to_sheet([
                ['Relatório de Vendas'],
                [''],
                ['Informações do Relatório:'],
                [`Data de Geração: ${new Date().toLocaleDateString('pt-BR')}`],
                [`Filtros Aplicados: ${filterInfo.join(', ')}`],
                [''],
                ['Total de Vendas:', data.length]
            ]);
            XLSX.utils.book_append_sheet(wb, filterWs, 'Informações');
        }
        
        // Salvar arquivo
        const fileName = `vendas_${new Date().toISOString().split('T')[0]}.xlsx`;
        XLSX.writeFile(wb, fileName);
        
        hideLoading();
        Swal.fire({
            title: 'Sucesso!',
            text: 'Relatório Excel exportado com sucesso!',
            icon: 'success',
            confirmButtonText: 'OK'
        });
        
    } catch (error) {
        hideLoading();
        console.error('Erro ao gerar Excel:', error);
        Swal.fire({
            title: 'Erro!',
            text: 'Erro ao gerar Excel. Verifique o console para mais detalhes.',
            icon: 'error',
            confirmButtonText: 'OK'
        });
    }
}

function confirmDeleteSell(sellId, clientName) {
    Swal.fire({
        title: 'Confirmar exclusão',
        text: `Tem certeza que deseja excluir a venda do cliente "${clientName}"?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            deleteSell(sellId);
        }
    });
}

function deleteSell(sellId) {
    showLoading();
    
    // Criar um formulário temporário para simular DELETE
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = `/sells/${sellId}`;
    form.style.display = 'none';
    
    // Adicionar campo _method para simular DELETE
    const methodField = document.createElement('input');
    methodField.type = 'hidden';
    methodField.name = '_method';
    methodField.value = 'DELETE';
    form.appendChild(methodField);
    
    // Adicionar CSRF token
    const csrfField = document.createElement('input');
    csrfField.type = 'hidden';
    csrfField.name = '_token';
    csrfField.value = $('meta[name="csrf-token"]').attr('content');
    form.appendChild(csrfField);
    
    // Adicionar ao DOM e submeter
    document.body.appendChild(form);
    
    // Fazer requisição AJAX POST com _method=DELETE
    $.ajax({
        url: `/sells/${sellId}`,
        method: 'POST',
        data: {
            _method: 'DELETE',
            _token: $('meta[name="csrf-token"]').attr('content')
        },
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Excluído!',
                text: 'Venda excluída com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao excluir venda.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 405) {
                errorMessage = 'Método não permitido. Tente novamente.';
            } else if (xhr.status === 404) {
                errorMessage = 'Venda não encontrada.';
            }
            
            console.error('Erro na exclusão:', xhr);
            
            Swal.fire({
                title: 'Erro!',
                text: errorMessage,
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
    
    // Remover formulário temporário
    document.body.removeChild(form);
}

function approvePayment(sellId, clientName) {
    Swal.fire({
        title: 'Aprovar Pagamento',
        text: `Tem certeza que deseja aprovar o pagamento da venda do cliente "${clientName}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, aprovar!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            approvePaymentAction(sellId);
        }
    });
}

function approvePaymentAction(sellId) {
    showLoading();
    
    $.ajax({
        url: `/sells/${sellId}/approve-payment`,
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: function(response) {
            hideLoading();
            Swal.fire({
                title: 'Aprovado!',
                text: 'Pagamento aprovado com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao aprovar pagamento.';
            
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

function openInstallmentsModal(sellId, clientName) {
    showLoading();
    
    $.ajax({
        url: `/sells/${sellId}/installments`,
        method: 'GET',
        success: function(response) {
            hideLoading();
            $('#installmentsContent').html(response);
            $('#installmentsModalLabel').html(`<i class="fas fa-credit-card me-2"></i>Parcelas - ${clientName}`);
            // Set the sell ID in the modal data
            $('#installmentsModal').data('sell-id', sellId);
            $('#installmentsModal').modal('show');
        },
        error: function(xhr) {
            hideLoading();
            Swal.fire({
                title: 'Erro!',
                text: 'Erro ao carregar parcelas.',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function markInstallmentAsPaidModal(installmentId, installmentName) {
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
            markInstallmentAsPaidActionModal(installmentId);
        }
    });
}

function markInstallmentAsPaidActionModal(installmentId) {
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
                // Reload the modal content
                const sellId = $('#installmentsModal').data('sell-id');
                const modalLabel = $('#installmentsModalLabel').text();
                const clientName = modalLabel.replace('Parcelas - ', '').replace(/^.*?Parcelas - /, '');
                
                if (sellId && sellId !== 'undefined') {
                    openInstallmentsModal(sellId, clientName);
                } else {
                    // Close modal if sell ID is not available
                    $('#installmentsModal').modal('hide');
                }
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