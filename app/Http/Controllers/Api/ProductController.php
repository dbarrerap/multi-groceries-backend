<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $request->validate([
                'length' => 'nullable|integer|min:1',
                'page' => 'nullable|integer|min:1',
                'name' => 'nullable|string|max:255',
            ]);

            $length = $request->input('length', 25);
            $page = $request->input('page', 1);

            $products = Product::query()
                ->with('category', 'unitOfMeasure')
                ->when($request->filled('name'), function ($query) use ($request) {
                    $query->where('name', 'ilike', '%' . $request->input('name') . '%');
                })
                ->orderBy('id', 'asc')
                ->paginate($length, ['*'], 'page', $page);
            return response()->json(['data' => $products]);
            
        } catch (\Exception $e) {
            logger()->error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:catalogs,id',
            'unit_of_measure_id' => 'nullable|exists:catalogs,id',
        ]);

        $product = Product::create($validated);
        return response()->json(['data' => $product->load('category', 'unitOfMeasure')], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product): JsonResponse
    {
        return response()->json(['data' => $product->load('category', 'unitOfMeasure')]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Product $product): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'brand' => 'nullable|string|max:255',
            'category_id' => 'nullable|exists:catalogs,id',
            'unit_of_measure_id' => 'nullable|exists:catalogs,id',
        ]);

        $product->update($validated);
        return response()->json(['data' => $product->load('category', 'unitOfMeasure')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): JsonResponse
    {
        $product->delete();
        return response()->json(['message' => 'Product deleted successfully'], 204);
    }
}
