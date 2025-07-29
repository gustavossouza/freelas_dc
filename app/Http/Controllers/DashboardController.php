<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Client;
use App\Models\Sell;
use App\Models\Product;
use App\Models\Installment;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Estatísticas gerais
        $totalClients = Client::count();
        $totalSells = Sell::count();
        $totalValue = Sell::sum('total_amount');
        $pendingInstallments = Installment::where('status', 'pending')->count();

        // Vendas recentes (últimas 5)
        $recentSells = Sell::with(['client'])
            ->latest('created_at')
            ->limit(5)
            ->get();

        // Parcelas vencidas
        $overdueInstallments = Installment::with(['sell.client'])
            ->where('status', 'pending')
            ->where('due_date', '<', today())
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Próximas parcelas (próximos 7 dias)
        $upcomingInstallments = Installment::with(['sell.client'])
            ->where('status', 'pending')
            ->whereBetween('due_date', [today(), today()->addDays(7)])
            ->orderBy('due_date')
            ->limit(5)
            ->get();

        // Estatísticas por forma de pagamento
        $paymentMethodStats = Sell::selectRaw('payment_method, COUNT(*) as count, SUM(total_amount) as total')
            ->groupBy('payment_method')
            ->get();

        // Vendas do mês atual
        $currentMonthSells = Sell::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $currentMonthValue = Sell::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('total_amount');

        // Parcelas vencidas hoje
        $todayOverdueInstallments = Installment::where('status', 'pending')
            ->where('due_date', '<', today())
            ->count();

        // Valor total de parcelas vencidas
        $overdueAmount = Installment::where('status', 'pending')
            ->where('due_date', '<', today())
            ->sum('amount');

        // Produtos ativos
        $activeProducts = Product::where('is_active', true)->count();

        return view('dashboard', compact(
            'totalClients',
            'totalSells',
            'totalValue',
            'pendingInstallments',
            'recentSells',
            'overdueInstallments',
            'upcomingInstallments',
            'paymentMethodStats',
            'currentMonthSells',
            'currentMonthValue',
            'todayOverdueInstallments',
            'overdueAmount',
            'activeProducts'
        ));
    }
} 