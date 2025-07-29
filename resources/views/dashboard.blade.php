@extends('layouts.app')

@section('title', 'Dashboard - Sistema de Vendas')

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card fade-in">
            <div class="card-header">
                <h4 class="mb-0">
                    <i class="fas fa-tachometer-alt me-2"></i>
                    Dashboard
                </h4>
            </div>
            <div class="card-body">
                <!-- Welcome Message -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5 class="alert-heading">
                                <i class="fas fa-hand-wave me-2"></i>
                                Bem-vindo, {{ Auth::user()->name }}!
                            </h5>
                            <p class="mb-0">Aqui você pode acompanhar todas as informações importantes do seu sistema de vendas.</p>
                        </div>
                    </div>
                </div>

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
                                        <h6 class="card-title">Total de Vendas</h6>
                                        <h3 class="mb-0">{{ $totalSells ?? 0 }}</h3>
                                        <small>Mês: {{ $currentMonthSells ?? 0 }}</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-shopping-cart fa-2x"></i>
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
                                        <small>Mês: R$ {{ number_format($currentMonthValue ?? 0, 2, ',', '.') }}</small>
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
                                        <h6 class="card-title">Parcelas Pendentes</h6>
                                        <h3 class="mb-0">{{ $pendingInstallments ?? 0 }}</h3>
                                        <small>Vencidas: {{ $todayOverdueInstallments ?? 0 }}</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Stats -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card bg-danger text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Parcelas Vencidas</h6>
                                        <h3 class="mb-0">{{ $todayOverdueInstallments ?? 0 }}</h3>
                                        <small>R$ {{ number_format($overdueAmount ?? 0, 2, ',', '.') }}</small>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card bg-secondary text-white">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <div>
                                        <h6 class="card-title">Produtos Ativos</h6>
                                        <h3 class="mb-0">{{ $activeProducts ?? 0 }}</h3>
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-box fa-2x"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card bg-dark text-white">
                            <div class="card-body">
                                <h6 class="card-title">
                                    <i class="fas fa-chart-pie me-2"></i>
                                    Formas de Pagamento
                                </h6>
                                <div class="row">
                                    @foreach($paymentMethodStats ?? [] as $stat)
                                    <div class="col-6">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ ucfirst(str_replace('_', ' ', $stat->payment_method)) }}</span>
                                            <span>{{ $stat->count }} vendas</span>
                                        </div>
                                        <div class="progress mt-1" style="height: 5px;">
                                            <div class="progress-bar" style="width: {{ $totalSells > 0 ? ($stat->count / $totalSells) * 100 : 0 }}%"></div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Quick Actions -->
                <div class="row mb-4">
                    <div class="col-12">
                        <h5 class="mb-3">
                            <i class="fas fa-bolt me-2"></i>
                            Ações Rápidas
                        </h5>
                        <div class="row">
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('clients.create') }}" class="btn btn-outline-primary w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-user-plus fa-2x mb-2"></i>
                                    <span>Novo Cliente</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('sells.create') }}" class="btn btn-outline-success w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <span>Nova Venda</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('products.create') }}" class="btn btn-outline-warning w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-plus fa-2x mb-2"></i>
                                    <span>Novo Produto</span>
                                </a>
                            </div>
                            <div class="col-md-3 mb-3">
                                <a href="{{ route('sells.index') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-list fa-2x mb-2"></i>
                                    <span>Ver Vendas</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-clock me-2"></i>
                                    Vendas Recentes
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($recentSells) && count($recentSells) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($recentSells as $sell)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">Venda #{{ $sell->id }}</h6>
                                                <small class="text-muted">{{ $sell->client->name ?? 'Cliente não encontrado' }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-success">R$ {{ number_format($sell->total_amount, 2, ',', '.') }}</span>
                                                <br>
                                                <small class="text-muted">{{ \Carbon\Carbon::parse($sell->created_at)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center">Nenhuma venda recente.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-exclamation-triangle me-2"></i>
                                    Parcelas Vencidas
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($overdueInstallments) && count($overdueInstallments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($overdueInstallments as $installment)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $installment->formatted_installment_number }}</h6>
                                                <small class="text-muted">{{ $installment->sell->client->name ?? 'Cliente não encontrado' }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-danger">R$ {{ number_format($installment->amount, 2, ',', '.') }}</span>
                                                <br>
                                                <small class="text-muted">Vencida há {{ \Carbon\Carbon::parse($installment->due_date)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center">Nenhuma parcela vencida.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">
                                    <i class="fas fa-calendar-check me-2"></i>
                                    Próximas Parcelas
                                </h6>
                            </div>
                            <div class="card-body">
                                @if(isset($upcomingInstallments) && count($upcomingInstallments) > 0)
                                    <div class="list-group list-group-flush">
                                        @foreach($upcomingInstallments as $installment)
                                        <div class="list-group-item d-flex justify-content-between align-items-center">
                                            <div>
                                                <h6 class="mb-1">{{ $installment->formatted_installment_number }}</h6>
                                                <small class="text-muted">{{ $installment->sell->client->name ?? 'Cliente não encontrado' }}</small>
                                            </div>
                                            <div class="text-end">
                                                <span class="badge bg-warning">R$ {{ number_format($installment->amount, 2, ',', '.') }}</span>
                                                <br>
                                                <small class="text-muted">Vence {{ \Carbon\Carbon::parse($installment->due_date)->diffForHumans() }}</small>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                @else
                                    <p class="text-muted text-center">Nenhuma parcela próxima.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-info, .card.bg-danger, .card.bg-secondary, .card.bg-dark {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card.bg-primary:hover, .card.bg-success:hover, .card.bg-warning:hover, .card.bg-info:hover, .card.bg-danger:hover, .card.bg-secondary:hover, .card.bg-dark:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .btn-outline-primary, .btn-outline-success, .btn-outline-warning, .btn-outline-info {
        border-radius: 12px;
        transition: all 0.3s ease;
        min-height: 120px;
    }
    
    .btn-outline-primary:hover, .btn-outline-success:hover, .btn-outline-warning:hover, .btn-outline-info:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    
    .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
        padding: 1rem 0;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }

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

    .progress {
        background-color: rgba(255, 255, 255, 0.2);
    }

    .progress-bar {
        background-color: rgba(255, 255, 255, 0.8);
    }
</style>
@endsection 