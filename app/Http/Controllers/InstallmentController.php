<?php

namespace App\Http\Controllers;

use App\Models\Installment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InstallmentController extends Controller
{
    /**
     * Mark an installment as paid.
     */
    public function markAsPaid(Installment $installment)
    {
        try {
            if ($installment->status !== 'pending') {
                return response()->json([
                    'message' => 'Apenas parcelas pendentes podem ser marcadas como pagas.'
                ], 400);
            }

            $installment->markAsPaid();

            // Check if all installments are paid to update sell payment status
            $sell = $installment->sell;
            $allInstallmentsPaid = $sell->installments()
                ->where('status', '!=', 'paid')
                ->count() === 0;

            if ($allInstallmentsPaid) {
                $sell->update(['payment_status' => 'paid']);
            }

            return response()->json([
                'message' => 'Parcela marcada como paga com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao marcar parcela como paga: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all pending installments as paid for a sell.
     */
    public function markAllPendingAsPaid(\App\Models\Sell $sell)
    {
        try {
            $pendingInstallments = $sell->installments()->where('status', 'pending')->get();
            
            if ($pendingInstallments->isEmpty()) {
                return response()->json([
                    'message' => 'Não há parcelas pendentes para marcar como pagas.'
                ], 400);
            }

            foreach ($pendingInstallments as $installment) {
                $installment->markAsPaid();
            }

            // Check if all installments are paid to update sell payment status
            $allInstallmentsPaid = $sell->installments()
                ->where('status', '!=', 'paid')
                ->count() === 0;

            if ($allInstallmentsPaid) {
                $sell->update(['payment_status' => 'paid']);
            }

            return response()->json([
                'message' => 'Todas as parcelas pendentes foram marcadas como pagas com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao marcar parcelas como pagas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all overdue installments as paid for a sell.
     */
    public function markOverdueAsPaid(\App\Models\Sell $sell)
    {
        try {
            $overdueInstallments = $sell->installments()
                ->where('status', 'pending')
                ->where('due_date', '<', today())
                ->get();
            
            if ($overdueInstallments->isEmpty()) {
                return response()->json([
                    'message' => 'Não há parcelas vencidas para marcar como pagas.'
                ], 400);
            }

            foreach ($overdueInstallments as $installment) {
                $installment->markAsPaid();
            }

            // Check if all installments are paid to update sell payment status
            $allInstallmentsPaid = $sell->installments()
                ->where('status', '!=', 'paid')
                ->count() === 0;

            if ($allInstallmentsPaid) {
                $sell->update(['payment_status' => 'paid']);
            }

            return response()->json([
                'message' => 'Todas as parcelas vencidas foram marcadas como pagas com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao marcar parcelas vencidas como pagas: ' . $e->getMessage()
            ], 500);
        }
    }
} 