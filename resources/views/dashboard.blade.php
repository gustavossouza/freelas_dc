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
                                    </div>
                                    <div class="align-self-center">
                                        <i class="fas fa-calendar-alt fa-2x"></i>
                                    </div>
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
                                <a href="{{ route('pdf.generate') }}" class="btn btn-outline-info w-100 h-100 d-flex flex-column align-items-center justify-content-center p-3">
                                    <i class="fas fa-file-pdf fa-2x mb-2"></i>
                                    <span>Gerar PDF</span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity -->
                <div class="row">
                    <div class="col-md-6">
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
                    <div class="col-md-6">
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
                                                <h6 class="mb-1">Parcela #{{ $installment->id }}</h6>
                                                <small class="text-muted">Venda #{{ $installment->sell_id }}</small>
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
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card.bg-primary, .card.bg-success, .card.bg-warning, .card.bg-info {
        border: none;
        border-radius: 12px;
        transition: all 0.3s ease;
    }
    
    .card.bg-primary:hover, .card.bg-success:hover, .card.bg-warning:hover, .card.bg-info:hover {
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
</style>
@endsection 