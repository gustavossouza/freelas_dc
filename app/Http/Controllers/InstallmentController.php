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
} 