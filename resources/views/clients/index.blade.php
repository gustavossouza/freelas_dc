@extends('layouts.app')

@section('title', 'Clientes - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="mb-0">
                    <i class="fas fa-users me-2"></i>
                    Clientes
                </h4>
                <a href="{{ route('clients.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>
                    Novo Cliente
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
                                        <h6 class="card-title">Total de Clientes</h6>
                                        <h3 class="mb-0">{{ $totalClients ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-users fa-2x"></i>
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
                                        <h6 class="card-title">Clientes Ativos</h6>
                                        <h3 class="mb-0">{{ $activeClients ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-user-check fa-2x"></i>
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
                                        <h6 class="card-title">Receita Total</h6>
                                        <h3 class="mb-0">R$ {{ number_format($totalRevenue ?? 0, 2, ',', '.') }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-dollar-sign fa-2x"></i>
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
                                        <h6 class="card-title">Ticket Médio</h6>
                                        <h3 class="mb-0">R$ {{ number_format($avgRevenuePerClient ?? 0, 2, ',', '.') }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-chart-line fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Search and Filters -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar clientes...">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <select class="form-select" id="stateFilter">
                            <option value="">Todos os Estados</option>
                            <option value="AC">Acre</option>
                            <option value="AL">Alagoas</option>
                            <option value="AP">Amapá</option>
                            <option value="AM">Amazonas</option>
                            <option value="BA">Bahia</option>
                            <option value="CE">Ceará</option>
                            <option value="DF">Distrito Federal</option>
                            <option value="ES">Espírito Santo</option>
                            <option value="GO">Goiás</option>
                            <option value="MA">Maranhão</option>
                            <option value="MT">Mato Grosso</option>
                            <option value="MS">Mato Grosso do Sul</option>
                            <option value="MG">Minas Gerais</option>
                            <option value="PA">Pará</option>
                            <option value="PB">Paraíba</option>
                            <option value="PR">Paraná</option>
                            <option value="PE">Pernambuco</option>
                            <option value="PI">Piauí</option>
                            <option value="RJ">Rio de Janeiro</option>
                            <option value="RN">Rio Grande do Norte</option>
                            <option value="RS">Rio Grande do Sul</option>
                            <option value="RO">Rondônia</option>
                            <option value="RR">Roraima</option>
                            <option value="SC">Santa Catarina</option>
                            <option value="SP">São Paulo</option>
                            <option value="SE">Sergipe</option>
                            <option value="TO">Tocantins</option>
                        </select>
                    </div>
                    <div class="col-md-3">
                        <button class="btn btn-outline-secondary w-100" onclick="exportClients()">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </button>
                    </div>
                </div>

                <!-- Clients Table -->
                <div class="table-responsive">
                    <table class="table table-hover" id="clientsTable">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Cliente</th>
                                <th>Contato</th>
                                <th>Endereço</th>
                                <th>Compras</th>
                                <th>Total Gasto</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($clients ?? [] as $client)
                            <tr>
                                <td>{{ $client->id }}</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="client-avatar me-3">
                                            <div class="avatar-placeholder">
                                                {{ strtoupper(substr($client->name, 0, 2)) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h6 class="mb-0">{{ $client->name }}</h6>
                                            @if($client->document)
                                                <small class="text-muted">{{ $client->formatted_document }}</small>
                                            @endif
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <div><i class="fas fa-envelope me-1"></i> {{ $client->email }}</div>
                                        @if($client->phone)
                                            <div><i class="fas fa-phone me-1"></i> {{ $client->formatted_phone }}</div>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        @if($client->address)
                                            <div>{{ $client->address }}</div>
                                        @endif
                                        @if($client->city || $client->state)
                                            <small class="text-muted">{{ $client->city }}{{ $client->city && $client->state ? ', ' : '' }}{{ $client->state }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-center">
                                        <span class="badge bg-primary">{{ $client->total_purchases }}</span>
                                        <br>
                                        <small class="text-muted">compras</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="text-end">
                                        <strong class="text-success">R$ {{ number_format($client->total_spent, 2, ',', '.') }}</strong>
                                        @if($client->total_purchases > 0)
                                            <br>
                                            <small class="text-muted">Méd: R$ {{ number_format($client->total_spent / $client->total_purchases, 2, ',', '.') }}</small>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('clients.show', $client->id) }}" 
                                           class="btn btn-sm btn-outline-primary" 
                                           title="Visualizar">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route('clients.edit', $client->id) }}" 
                                           class="btn btn-sm btn-outline-warning" 
                                           title="Editar">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <button type="button" 
                                                class="btn btn-sm btn-outline-danger" 
                                                title="Excluir"
                                                onclick="confirmDeleteClient({{ $client->id }}, '{{ $client->name }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-4">
                                    <div class="empty-state">
                                        <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                        <h5>Nenhum cliente encontrado</h5>
                                        <p class="text-muted">Comece adicionando seu primeiro cliente.</p>
                                        <a href="{{ route('clients.create') }}" class="btn btn-primary">
                                            <i class="fas fa-plus me-2"></i>
                                            Adicionar Cliente
                                        </a>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if(isset($clients) && $clients->hasPages())
                <div class="d-flex justify-content-center mt-4">
                    {{ $clients->links() }}
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
    .client-avatar {
        width: 40px;
        height: 40px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .avatar-placeholder {
        width: 40px;
        height: 40px;
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
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
    
    .card.bg-primary, .card.bg-success, .card.bg-info, .card.bg-warning {
        border: none;
        border-radius: 12px;
    }
</style>
@endsection

@section('scripts')
<!-- SweetAlert2 JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<!-- jsPDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.29/jspdf.plugin.autotable.min.js"></script>
<!-- XLSX -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
<script>
$(document).ready(function() {
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const value = $(this).val().toLowerCase();
        $('#clientsTable tbody tr').filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
        });
    });
    
    // State filter
    $('#stateFilter').on('change', function() {
        filterTable();
    });
    
    // Row click to view details
    $('#clientsTable tbody tr').on('click', function(e) {
        if (!$(e.target).closest('.btn-group').length) {
            const clientId = $(this).find('td:first').text();
            window.location.href = `/clients/${clientId}`;
        }
    });
    
    // Tooltip initialization
    $('[title]').tooltip();
});

function filterTable() {
    const state = $('#stateFilter').val();
    
    $('#clientsTable tbody tr').each(function() {
        const row = $(this);
        const stateText = row.find('td:nth-child(4) small').text().toLowerCase();
        
        if (state && !stateText.includes(state.toLowerCase())) {
            row.hide();
        } else {
            row.show();
        }
    });
}

function exportClients() {
    Swal.fire({
        title: 'Exportar Clientes',
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
            exportClientsToPDF();
        } else if (result.isDenied) {
            exportClientsToExcel();
        }
    });
}

function exportClientsToPDF() {
    showLoading();
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Título
        doc.setFontSize(18);
        doc.text('Relatório de Clientes', 14, 22);
        
        // Data de geração
        doc.setFontSize(10);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')}`, 14, 32);
        
        // Filtros aplicados
        const filters = [];
        if ($('#searchInput').val()) filters.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#stateFilter').val()) filters.push(`Estado: ${$('#stateFilter option:selected').text()}`);
        
        if (filters.length > 0) {
            doc.text(`Filtros: ${filters.join(', ')}`, 14, 42);
        }
        
        // Dados da tabela
        const tableData = [];
        const headers = ['ID', 'Cliente', 'Contato', 'Endereço', 'Vendas', 'Receita'];
        
        $('#clientsTable tbody tr:visible').each(function() {
            const row = [];
            const cells = $(this).find('td');
            
            // ID
            row.push($(cells[0]).text().trim());
            
            // Cliente
            row.push($(cells[1]).find('h6').text().trim());
            
            // Contato (Email e Telefone)
            const email = $(cells[2]).find('div:first').text().trim();
            const phone = $(cells[2]).find('div:last').text().trim();
            row.push(`${email} ${phone}`.trim());
            
            // Endereço
            const address = $(cells[3]).find('div:first').text().trim();
            const cityState = $(cells[3]).find('small').text().trim();
            row.push(`${address} ${cityState}`.trim());
            
            // Vendas
            const salesText = $(cells[4]).find('.badge').text().trim();
            row.push(salesText);
            
            // Receita
            const revenueText = $(cells[5]).find('strong').text().trim();
            row.push(revenueText);
            
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
        const fileName = `clientes_${new Date().toISOString().split('T')[0]}.pdf`;
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

function exportClientsToExcel() {
    showLoading();
    
    try {
        // Preparar dados
        const data = [];
        const headers = ['ID', 'Cliente', 'Email', 'Telefone', 'Endereço', 'Cidade', 'Estado', 'Vendas', 'Receita Total'];
        
        $('#clientsTable tbody tr:visible').each(function() {
            const row = {};
            const cells = $(this).find('td');
            
            // ID
            row['ID'] = $(cells[0]).text().trim();
            
            // Cliente
            row['Cliente'] = $(cells[1]).find('h6').text().trim();
            
            // Email
            const email = $(cells[2]).find('div:first').text().trim();
            row['Email'] = email.replace(/[📧✉️]/g, '').replace(/\s+/g, ' ').trim();
            
            // Telefone
            const phone = $(cells[2]).find('div:last').text().trim();
            row['Telefone'] = phone.replace(/[📞📱]/g, '').replace(/\s+/g, ' ').trim();
            
            // Endereço
            const address = $(cells[3]).find('div:first').text().trim();
            row['Endereço'] = address || '-';
            
            // Cidade e Estado
            const cityState = $(cells[3]).find('small').text().trim();
            const cityMatch = cityState.match(/(.+?),\s*([A-Z]{2})/);
            row['Cidade'] = cityMatch ? cityMatch[1].trim() : cityState;
            row['Estado'] = cityMatch ? cityMatch[2].trim() : '-';
            
            // Vendas
            row['Vendas'] = $(cells[4]).find('.badge').text().trim();
            
            // Receita Total
            row['Receita Total'] = $(cells[5]).find('strong').text().trim();
            
            data.push(row);
        });
        
        // Criar workbook
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(data);
        
        // Configurar largura das colunas
        const colWidths = [
            { wch: 8 },   // ID
            { wch: 30 },  // Cliente
            { wch: 30 },  // Email
            { wch: 15 },  // Telefone
            { wch: 25 },  // Endereço
            { wch: 20 },  // Cidade
            { wch: 10 },  // Estado
            { wch: 12 },  // Vendas
            { wch: 20 }   // Receita Total
        ];
        ws['!cols'] = colWidths;
        
        // Adicionar worksheet ao workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Clientes');
        
        // Adicionar informações de filtros
        const filterInfo = [];
        if ($('#searchInput').val()) filterInfo.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#stateFilter').val()) filterInfo.push(`Estado: ${$('#stateFilter option:selected').text()}`);
        
        if (filterInfo.length > 0) {
            const filterWs = XLSX.utils.aoa_to_sheet([
                ['Relatório de Clientes'],
                [''],
                ['Informações do Relatório:'],
                [`Data de Geração: ${new Date().toLocaleDateString('pt-BR')}`],
                [`Filtros Aplicados: ${filterInfo.join(', ')}`],
                [''],
                ['Total de Clientes:', data.length]
            ]);
            XLSX.utils.book_append_sheet(wb, filterWs, 'Informações');
        }
        
        // Salvar arquivo
        const fileName = `clientes_${new Date().toISOString().split('T')[0]}.xlsx`;
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
                text: 'Cliente excluído com sucesso.',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then(() => {
                location.reload();
            });
        },
        error: function(xhr) {
            hideLoading();
            let errorMessage = 'Erro ao excluir cliente.';
            
            if (xhr.responseJSON && xhr.responseJSON.message) {
                errorMessage = xhr.responseJSON.message;
            } else if (xhr.status === 405) {
                errorMessage = 'Método não permitido. Tente novamente.';
            } else if (xhr.status === 404) {
                errorMessage = 'Cliente não encontrado.';
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
}
</script>
@endsection 