<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\ShoppingRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class ShoppingRecordController extends Controller
{
    use ApiResponse;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) 
    {
        try {
            $request->validate([
                'length' => 'nullable|integer|min:1',
                'page' => 'nullable|integer|min:1',
            ]);
            $length = $request->input('length', 25);
            $page = $request->input('page', 1);

            $shoppingRecords = ShoppingRecord::query()
                ->where('user_id', Auth::id())
                ->with(['store', 'shoppingItems.product'])
                ->orderBy('date', 'asc')
                ->paginate($length, ['*'], 'page', $page);

            return $this->successResponse($shoppingRecords);
        } catch (\Exception $e) {
            logger()->error('Error fetching products: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch products'], 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): Response
    {
        $validated = $request->validate([
            'store_id' => 'required|exists:stores,id',
            'date' => 'required|date',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $shoppingRecord = Auth::user()->shoppingRecords()->create([
            'store_id' => $validated['store_id'],
            'date' => $validated['date'],
        ]);

        foreach ($validated['items'] as $item) {
            $shoppingRecord->shoppingItems()->create($item);
        }

        return response($shoppingRecord->load(['store', 'shoppingItems.product']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ShoppingRecord $shoppingRecord): Response
    {
        // Ensure the authenticated user owns this shopping record
        if ($shoppingRecord->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        return response($shoppingRecord->load(['store', 'shoppingItems.product']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ShoppingRecord $shoppingRecord): Response
    {
        // Ensure the authenticated user owns this shopping record
        if ($shoppingRecord->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }

        $validated = $request->validate([
            'store_id' => 'sometimes|required|exists:stores,id',
            'date' => 'sometimes|required|date',
            'items' => 'sometimes|required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.price' => 'required|numeric|min:0',
            'items.*.quantity' => 'required|numeric|min:0',
        ]);

        $shoppingRecord->update($validated);

        if (isset($validated['items'])) {
            $shoppingRecord->shoppingItems()->delete(); // Delete existing items
            foreach ($validated['items'] as $item) {
                $shoppingRecord->shoppingItems()->create($item);
            }
        }

        return response($shoppingRecord->load(['store', 'shoppingItems.product']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ShoppingRecord $shoppingRecord)
    {
        // Ensure the authenticated user owns this shopping record
        if ($shoppingRecord->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        $shoppingRecord->delete();
        return $this->successResponse(['message' => 'Shopping record deleted successfully'], Response::HTTP_NO_CONTENT);
    }
}
