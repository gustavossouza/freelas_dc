<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $products = Product::orderBy('name')
                ->paginate(15);

            $totalProducts = Product::count();
            $activeProducts = Product::active()->count();
            $outOfStockProducts = Product::where('stock_quantity', 0)->count();

            return view('products.index', compact(
                'products',
                'totalProducts',
                'activeProducts',
                'outOfStockProducts'
            ));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar produtos: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        try {
            return view('products.create');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProductRequest $request)
    {
        try {
            $product = Product::create($request->validated());

            return redirect()->route('products.index')
                ->with('success', 'Produto criado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao criar produto: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        try {
            $product->load('sellItems.sell.client');
            
            return view('products.show', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar produto: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        try {
            return view('products.edit', compact('product'));
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao carregar formulário: ' . $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, Product $product)
    {
        try {
            $product->update($request->validated());

            return redirect()->route('products.index')
                ->with('success', 'Produto atualizado com sucesso!');
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Erro ao atualizar produto: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Check if product has associated sell items
            if ($product->sellItems()->count() > 0) {
                return back()->with('error', 'Não é possível excluir um produto que possui vendas associadas.');
            }

            $product->delete();

            return redirect()->route('products.index')
                ->with('success', 'Produto excluído com sucesso!');
        } catch (\Exception $e) {
            return back()->with('error', 'Erro ao excluir produto: ' . $e->getMessage());
        }
    }

    /**
     * Get products for AJAX requests (for sell form).
     */
    public function getProducts(Request $request)
    {
        try {
            $search = $request->get('search');
            
            $products = Product::active()
                ->when($search, function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('name', 'like', "%{$search}%");
                    });
                })
                ->select('id', 'name', 'description', 'price', 'unit')
                ->limit(10)
                ->get();

            return response()->json($products);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Erro ao buscar produtos'], 500);
        }
    }

    /**
     * Get product details by ID.
     */
    public function getProductDetails($id)
    {
        try {
            $product = Product::active()
                ->select('id', 'name', 'sku', 'price', 'stock_quantity', 'unit', 'description')
                ->findOrFail($id);

            return response()->json($product);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Produto não encontrado'], 404);
        }
    }
} 