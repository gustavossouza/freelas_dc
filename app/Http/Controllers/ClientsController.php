<?php

namespace App\Http\Controllers;

use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ClientRequest;

class ClientsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $clients = Client::with(['sells'])
                ->orderBy('name')
                ->paginate(15);

            $totalClients = Client::count();
            $activeClients = Client::count(); // Todos os clientes são considerados ativos
            $totalRevenue = Client::with('sells')->get()->sum(function($client) {
                return $client->sells->sum('total_amount');
            });
            $avgRevenuePerClient = $totalClients > 0 ? $totalRevenue / $totalClients : 0;

            return view('clients.index', compact(
                'clients',
                'totalClients',
                'activeClients',
                'totalRevenue',
                'avgRevenuePerClient'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar clientes: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('clients.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ClientRequest $request)
    {
        try {
            $client = Client::create($request->validated());

            return redirect()->route('clients.index')
                ->with('success', 'Cliente criado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Client $client)
    {
        try {
            $client->load(['sells.sellItems.product', 'sells.installments']);
            
            // Estatísticas do cliente
            $totalPurchases = $client->sells->count();
            $totalSpent = $client->sells->sum('total_amount');
            $avgPurchaseValue = $totalPurchases > 0 ? $totalSpent / $totalPurchases : 0;
            $lastPurchase = $client->sells->sortByDesc('sale_date')->first();
            $pendingInstallments = $client->sells->flatMap->installments->where('status', 'pending');
            
            return view('clients.show', compact(
                'client',
                'totalPurchases',
                'totalSpent',
                'avgPurchaseValue',
                'lastPurchase',
                'pendingInstallments'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Client $client)
    {
        try {
            return view('clients.edit', compact('client'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ClientRequest $request, Client $client)
    {
        try {
            $client->update($request->validated());

            return redirect()->route('clients.index')
                ->with('success', 'Cliente atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar cliente: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Client $client)
    {
        try {
            // Check if client has associated sells
            if ($client->sells()->count() > 0) {
                return back()->with('error', 'Não é possível excluir um cliente que possui vendas associadas.');
            }

            $client->delete();

            return redirect()->route('clients.index')
                ->with('success', 'Cliente excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir cliente: ' . $e->getMessage());
        }
    }

    /**
     * Get clients for AJAX requests (for sell form).
     */
    public function getClients(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $clients = Client::when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%")
                          ->orWhere('email', 'like', "%{$search}%")
                          ->orWhere('document', 'like', "%{$search}%");
                    });
                })
                ->select('id', 'name', 'email', 'phone', 'document')
                ->limit(10)
                ->get();

            return response()->json($clients);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar clientes'], 500);
        }
    }

    /**
     * Get client details by ID.
     */
    public function getClientDetails($id)
    {
        try {
            $client = Client::select('id', 'name', 'email', 'phone', 'document', 'address', 'city', 'state')
                ->findOrFail($id);

            return response()->json($client);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Cliente não encontrado'], 404);
        }
    }

    /**
     * Check if client has sales before deletion.
     */
    public function checkSales(Client $client)
    {
        try {
            $salesCount = $client->sells()->count();
            
            return response()->json([
                'hasSales' => $salesCount > 0,
                'salesCount' => $salesCount
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao verificar vendas'], 500);
        }
    }
} 