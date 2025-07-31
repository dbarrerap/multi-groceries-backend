<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse; // Importar el trait
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreController extends Controller
{
    use ApiResponse; // Usar el trait

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

            $stores = Store::query()
                ->when($request->filled('name'), function ($query) use ($request) {
                    $query->where('name', 'ilike', '%' . $request->input('name') . '%');
                })
                ->orderBy('id', 'asc')
                ->paginate($length, ['*'], 'page', $page);

            return $this->successResponse($stores);
        } catch (\Exception $e) {
            logger()->error('Error fetching stores: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch stores', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'location' => 'nullable|string|max:255',
            ]);

            $store = Store::create($validated);
            return $this->createdResponse($store);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            logger()->error('Error creating store: ' . $e->getMessage());
            return $this->errorResponse('Failed to create store', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Store $store): JsonResponse
    {
        return $this->successResponse($store);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Store $store): JsonResponse
    {
        try {
            $validated = $request->validate([
                'name' => 'sometimes|required|string|max:255',
                'location' => 'nullable|string|max:255',
            ]);

            $store->update($validated);
            return $this->successResponse($store);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->errors());
        } catch (\Exception $e) {
            logger()->error('Error updating store: ' . $e->getMessage());
            return $this->errorResponse('Failed to update store', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Store $store): JsonResponse
    {
        try {
            $store->delete();
            return $this->successResponse([], Response::HTTP_NO_CONTENT);
        } catch (\Exception $e) {
            logger()->error('Error deleting store: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete store', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
