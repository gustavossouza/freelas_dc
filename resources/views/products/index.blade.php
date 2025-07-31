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
                    <div class="col-md-4 col-sm-12 mb-2">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="fas fa-search"></i>
                            </span>
                            <input type="text" id="searchInput" class="form-control" placeholder="Buscar produtos...">
                        </div>
                    </div>
                    <div class="col-md-3 col-sm-6 mb-2">
                        <select class="form-select" id="statusFilter">
                            <option value="">Todos os Status</option>
                            <option value="active">Ativos</option>
                            <option value="inactive">Inativos</option>
                        </select>
                    </div>

                    <div class="col-md-2 col-sm-3 mb-2">
                        <button class="btn btn-outline-secondary w-100" onclick="exportProducts()">
                            <i class="fas fa-download me-1"></i>
                            Exportar
                        </button>
                    </div>
                    
                    <div class="col-md-2 col-sm-3 mb-2">
                        <button class="btn btn-outline-danger w-100" onclick="clearFilters()">
                            <i class="fas fa-times me-1"></i>
                            Limpar
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
    
    #resultsCounter {
        border-radius: 12px;
        border: none;
        background: linear-gradient(135deg, #e3f2fd, #bbdefb);
        color: #1565c0;
        font-weight: 500;
    }
    
    #resultsCounter i {
        color: #1976d2;
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
        filterTable();
    });
    
    // Status filter
    $('#statusFilter').on('change', function() {
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
    const searchValue = $('#searchInput').val().toLowerCase();
    const statusFilter = $('#statusFilter').val();
    
    $('#productsTable tbody tr').each(function() {
        const row = $(this);
        let showRow = true;
        
        // Filtro de busca (nome e descrição)
        if (searchValue) {
            const productName = row.find('td:nth-child(2) h6').text().toLowerCase();
            const productDescription = row.find('td:nth-child(2) small').text().toLowerCase();
            const productText = productName + ' ' + productDescription;
            
            if (!productText.includes(searchValue)) {
                showRow = false;
            }
        }
        
        // Filtro de status
        if (statusFilter && showRow) {
            const statusText = row.find('td:nth-child(5) .badge').text().toLowerCase();
            
            if (statusFilter === 'active' && !statusText.includes('ativo')) {
                showRow = false;
            } else if (statusFilter === 'inactive' && !statusText.includes('inativo')) {
                showRow = false;
            }
        }
        
        row.toggle(showRow);
    });
    
    // Atualizar contador de resultados
    updateResultsCounter();
}

function updateResultsCounter() {
    const visibleCount = $('#productsTable tbody tr:visible').length;
    const totalCount = $('#productsTable tbody tr').length;
    
    // Se houver filtros ativos, mostrar contador
    const hasFilters = $('#searchInput').val() || $('#statusFilter').val();
    
    if (hasFilters) {
        if (!$('#resultsCounter').length) {
            $('#productsTable').before('<div id="resultsCounter" class="alert alert-info mb-3"><i class="fas fa-info-circle me-2"></i><span></span></div>');
        }
        $('#resultsCounter span').text(`${visibleCount} de ${totalCount} produtos encontrados`);
        $('#resultsCounter').show();
    } else {
        $('#resultsCounter').hide();
    }
}

function clearFilters() {
    $('#searchInput').val('');
    $('#statusFilter').val('');
    filterTable();
    
    Swal.fire({
        title: 'Filtros Limpos!',
        text: 'Todos os filtros foram removidos.',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
}

function exportProducts() {
    Swal.fire({
        title: 'Exportar Produtos',
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
            exportProductsToPDF();
        } else if (result.isDenied) {
            exportProductsToExcel();
        }
    });
}

function exportProductsToPDF() {
    showLoading();
    
    try {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF();
        
        // Título
        doc.setFontSize(18);
        doc.text('Relatório de Produtos', 14, 22);
        
        // Data de geração
        doc.setFontSize(10);
        doc.text(`Gerado em: ${new Date().toLocaleDateString('pt-BR')}`, 14, 32);
        
        // Filtros aplicados
        const filters = [];
        if ($('#searchInput').val()) filters.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#statusFilter').val()) filters.push(`Status: ${$('#statusFilter option:selected').text()}`);
        
        if (filters.length > 0) {
            doc.text(`Filtros: ${filters.join(', ')}`, 14, 42);
        }
        
        // Dados da tabela
        const tableData = [];
        const headers = ['ID', 'Produto', 'Preço', 'Unidade', 'Status'];
        
        $('#productsTable tbody tr:visible').each(function() {
            const row = [];
            const cells = $(this).find('td');
            
            // ID
            row.push($(cells[0]).text().trim());
            
            // Produto
            row.push($(cells[1]).find('h6').text().trim());
            
            // Preço
            row.push($(cells[2]).find('strong').text().trim());
            
            // Unidade
            row.push($(cells[3]).find('strong').text().trim());
            
            // Status
            row.push($(cells[4]).find('.badge').text().trim());
            
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
        const fileName = `produtos_${new Date().toISOString().split('T')[0]}.pdf`;
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

function exportProductsToExcel() {
    showLoading();
    
    try {
        // Preparar dados
        const data = [];
        const headers = ['ID', 'Produto', 'Descrição', 'Preço', 'Unidade', 'Status'];
        
        $('#productsTable tbody tr:visible').each(function() {
            const row = {};
            const cells = $(this).find('td');
            
            // ID
            row['ID'] = $(cells[0]).text().trim();
            
            // Produto
            row['Produto'] = $(cells[1]).find('h6').text().trim();
            
            // Descrição
            const description = $(cells[1]).find('small').text().trim();
            row['Descrição'] = description || '-';
            
            // Preço
            row['Preço'] = $(cells[2]).find('strong').text().trim();
            
            // Unidade
            row['Unidade'] = $(cells[3]).find('strong').text().trim();
            
            // Status
            row['Status'] = $(cells[4]).find('.badge').text().trim();
            
            data.push(row);
        });
        
        // Criar workbook
        const wb = XLSX.utils.book_new();
        const ws = XLSX.utils.json_to_sheet(data);
        
        // Configurar largura das colunas
        const colWidths = [
            { wch: 8 },   // ID
            { wch: 30 },  // Produto
            { wch: 40 },  // Descrição
            { wch: 15 },  // Preço
            { wch: 12 },  // Unidade
            { wch: 15 }   // Status
        ];
        ws['!cols'] = colWidths;
        
        // Adicionar worksheet ao workbook
        XLSX.utils.book_append_sheet(wb, ws, 'Produtos');
        
        // Adicionar informações de filtros
        const filterInfo = [];
        if ($('#searchInput').val()) filterInfo.push(`Busca: ${$('#searchInput').val()}`);
        if ($('#statusFilter').val()) filterInfo.push(`Status: ${$('#statusFilter option:selected').text()}`);
        
        if (filterInfo.length > 0) {
            const filterWs = XLSX.utils.aoa_to_sheet([
                ['Relatório de Produtos'],
                [''],
                ['Informações do Relatório:'],
                [`Data de Geração: ${new Date().toLocaleDateString('pt-BR')}`],
                [`Filtros Aplicados: ${filterInfo.join(', ')}`],
                [''],
                ['Total de Produtos:', data.length]
            ]);
            XLSX.utils.book_append_sheet(wb, filterWs, 'Informações');
        }
        
        // Salvar arquivo
        const fileName = `produtos_${new Date().toISOString().split('T')[0]}.xlsx`;
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
            } else if (xhr.status === 405) {
                errorMessage = 'Método não permitido. Tente novamente.';
            } else if (xhr.status === 404) {
                errorMessage = 'Produto não encontrado.';
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