<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Catalog;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CatalogController extends Controller
{
    use ApiResponse;

    public function index(Request $request)
    {
        try {
            $query = Catalog::query();

            if ($request->has('code')) {
                $parent = Catalog::where('code', $request->input('code'))->whereNull('parent_id')->first();
                if ($parent) {
                    $query->where('parent_id', $parent->id);
                } else {
                    // Return no results if code does not match a parent catalog
                    $query->whereRaw('1 = 0');
                }
            } elseif ($request->has('parent_id')) {
                $query->where('parent_id', $request->input('parent_id'));
            } else {
                // By default, return root catalogs if no filter is applied
                $query->whereNull('parent_id')->with('children');
            }

            $catalogs = $query->orderBy('name')->get();
            return $this->successResponse($catalogs);

        } catch (Exception $e) {
            logger()->error('Error fetching catalogs: ' . $e->getMessage());
            return $this->errorResponse('Failed to fetch catalogs', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:255',
            'parent_id' => 'nullable|exists:catalogs,id',
        ]);

        try {
            $catalog = Catalog::create($validated);
            return $this->createdResponse($catalog);
        } catch (Exception $e) {
            logger()->error('Error creating catalog: ' . $e->getMessage());
            return $this->errorResponse('Failed to create catalog', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Catalog $catalog)
    {
        return $this->successResponse($catalog->load('children'));
    }

    public function update(Request $request, Catalog $catalog)
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'code' => 'sometimes|required|string|max:255',
            'parent_id' => 'nullable|exists:catalogs,id',
        ]);

        try {
            $catalog->update($validated);
            return $this->successResponse($catalog);
        } catch (Exception $e) {
            logger()->error('Error updating catalog: ' . $e->getMessage());
            return $this->errorResponse('Failed to update catalog', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Catalog $catalog)
    {
        try {
            $catalog->delete();
            return $this->successResponse(null, Response::HTTP_NO_CONTENT);
        } catch (Exception $e) {
            logger()->error('Error deleting catalog: ' . $e->getMessage());
            return $this->errorResponse('Failed to delete catalog', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
