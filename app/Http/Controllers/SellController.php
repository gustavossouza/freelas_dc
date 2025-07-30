<?php

namespace App\Http\Controllers;

use App\Models\Sell;
use App\Models\SellItem;
use App\Models\Client;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\SellRequest;
use Carbon\Carbon;

class SellController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $sells = Sell::with(['client', 'sellItems', 'installments'])
                ->orderBy('created_at', 'desc')
                ->paginate(15);

            $totalSells = Sell::count();
            $todaySells = Sell::whereDate('sale_date', today())->count();
            $totalValue = Sell::sum('total_amount');
            $pendingSells = Sell::where('status', 'pending')->count();

            return view('sells.index', compact(
                'sells',
                'totalSells',
                'todaySells',
                'totalValue',
                'pendingSells'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar vendas: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            $clients = Client::orderBy('name')->get();
            
            return view('sells.create', compact('clients'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SellRequest $request)
    {
        try {
            DB::beginTransaction();

            // Calculate total from items
            $subtotal = 0;
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    $subtotal += $item['total_price'];
                }
            }
            
            $discount = $request->discount ?? 0;
            $total_amount = $subtotal - $discount;

            // Create sell
            $sell = Sell::create([
                'client_id' => $request->client_id,
                'user_id' => auth()->id(),
                'payment_method' => $request->payment_method,
                'total_amount' => $total_amount,
                'discount' => $discount,
                'status' => 'pending', // Default status
                'payment_status' => 'pending',
                'sale_date' => $request->sale_date,
                'due_date' => $request->due_date,
                'notes' => $request->notes,
            ]);

            // Create sell items
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    SellItem::create([
                        'sell_id' => $sell->id,
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);

                    // Update product stock if product_id exists
                    if (!empty($item['product_id'])) {
                        $product = \App\Models\Product::find($item['product_id']);
                        if ($product) {
                            $product->decrement('stock_quantity', $item['quantity']);
                        }
                    }
                }
            }

            // Generate installments for credit card payments
            if ($request->payment_method === 'cartao_credito' && $request->installments > 1) {
                // Check if custom installments are provided
                if ($request->has('custom_installments') && is_array($request->custom_installments)) {
                    foreach ($request->custom_installments as $installment) {
                        \App\Models\Installment::create([
                            'sell_id' => $sell->id,
                            'installment_number' => $installment['number'],
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'status' => 'pending',
                            'notes' => "Parcela {$installment['number']}",
                        ]);
                    }
                } else {
                    // Use default installment calculation
                    $installmentAmount = $total_amount / $request->installments;
                    
                    // Parse due_date to Carbon instance
                    if ($request->due_date) {
                        $firstDueDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->due_date);
                    } else {
                        $firstDueDate = now()->addMonth();
                    }
                    
                    for ($i = 1; $i <= $request->installments; $i++) {
                        $dueDate = $firstDueDate->copy()->addMonths($i - 1);
                        
                        \App\Models\Installment::create([
                            'sell_id' => $sell->id,
                            'installment_number' => $i,
                            'amount' => $installmentAmount,
                            'due_date' => $dueDate,
                            'status' => 'pending',
                            'notes' => "Parcela {$i} de {$request->installments}",
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->route('sells.index')
                ->with('success', 'Venda criada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar venda: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Sell $sell)
    {
        try {
            $sell->load(['client', 'sellItems.product', 'installments']);
            
            return view('sells.show', compact('sell'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar venda: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Sell $sell)
    {
        try {
            $clients = Client::orderBy('name')->get();
            $sell->load(['sellItems', 'installments']);
            
            return view('sells.edit', compact('sell', 'clients'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SellRequest $request, Sell $sell)
    {
        try {
            DB::beginTransaction();

            // Calculate total from items
            $subtotal = 0;
            if ($request->has('items') && is_array($request->items)) {
                foreach ($request->items as $item) {
                    $subtotal += $item['total_price'];
                }
            }
            
            $discount = $request->discount ?? 0;
            $total_amount = $subtotal - $discount;

            // Update sell
            $sell->update([
                'client_id' => $request->client_id,
                'payment_method' => $request->payment_method,
                'total_amount' => $total_amount,
                'discount' => $discount,
                'status' => $request->status,
                'sale_date' => $request->sale_date,
                'due_date' => $request->due_date,
                'notes' => $request->notes,
            ]);

            // Update sell items
            if ($request->has('items') && is_array($request->items)) {
                // Delete existing items
                $sell->sellItems()->delete();
                
                // Create new items
                foreach ($request->items as $item) {
                    SellItem::create([
                        'sell_id' => $sell->id,
                        'product_id' => $item['product_id'] ?? null,
                        'product_name' => $item['product_name'],
                        'description' => $item['description'] ?? null,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['unit_price'],
                        'total_price' => $item['total_price'],
                    ]);

                    // Update product stock if product_id exists
                    if (!empty($item['product_id'])) {
                        $product = \App\Models\Product::find($item['product_id']);
                        if ($product) {
                            $product->decrement('stock_quantity', $item['quantity']);
                        }
                    }
                }
            }

            // Handle installments for credit card payments
            if ($request->payment_method === 'cartao_credito' && $request->installments > 1) {
                // Delete existing installments
                $sell->installments()->delete();
                
                // Check if custom installments are provided
                if ($request->has('custom_installments') && is_array($request->custom_installments)) {
                    foreach ($request->custom_installments as $installment) {
                        \App\Models\Installment::create([
                            'sell_id' => $sell->id,
                            'installment_number' => $installment['number'],
                            'amount' => $installment['amount'],
                            'due_date' => $installment['due_date'],
                            'status' => 'pending',
                            'notes' => "Parcela {$installment['number']}",
                        ]);
                    }
                } else {
                    // Use default installment calculation
                    $installmentAmount = $total_amount / $request->installments;
                    
                    // Parse due_date to Carbon instance
                    if ($request->due_date) {
                        $firstDueDate = \Carbon\Carbon::createFromFormat('Y-m-d', $request->due_date);
                    } else {
                        $firstDueDate = now()->addMonth();
                    }
                    
                    for ($i = 1; $i <= $request->installments; $i++) {
                        $dueDate = $firstDueDate->copy()->addMonths($i - 1);
                        
                        \App\Models\Installment::create([
                            'sell_id' => $sell->id,
                            'installment_number' => $i,
                            'amount' => $installmentAmount,
                            'due_date' => $dueDate,
                            'status' => 'pending',
                            'notes' => "Parcela {$i} de {$request->installments}",
                        ]);
                    }
                }
            } else {
                // Remove installments if payment method changed
                $sell->installments()->delete();
            }

            DB::commit();

            return redirect()->route('sells.index')
                ->with('success', 'Venda atualizada com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar venda: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Sell $sell)
    {
        try {
            DB::beginTransaction();

            // Delete sell (this will cascade delete items and installments)
            $sell->delete();

            DB::commit();

            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Venda excluída com sucesso!'
                ]);
            }

            return redirect()->route('sells.index')
                ->with('success', 'Venda excluída com sucesso!');
        } catch (\Exception $e) {
            DB::rollBack();
            
            if (request()->expectsJson()) {
                return response()->json([
                    'message' => 'Erro ao excluir venda: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Erro ao excluir venda: ' . $e->getMessage());
        }
    }

    /**
     * Approve payment for a sell.
     */
    public function approvePayment(Sell $sell)
    {
        try {
            if ($sell->payment_status !== 'pending') {
                return response()->json([
                    'message' => 'Apenas vendas com pagamento pendente podem ser aprovadas.'
                ], 400);
            }

            $sell->update([
                'payment_status' => 'paid'
            ]);

            return response()->json([
                'message' => 'Pagamento aprovado com sucesso!'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao aprovar pagamento: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get installments for modal view.
     */
    public function getInstallments(Sell $sell)
    {
        try {
            $sell->load(['client', 'installments' => function($query) {
                $query->orderBy('installment_number');
            }]);
            
            return view('sells.installments-modal', compact('sell'));
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Erro ao carregar parcelas: ' . $e->getMessage()
            ], 500);
        }
    }
} 