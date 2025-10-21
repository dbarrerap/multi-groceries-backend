<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class StoreComparisonController extends Controller
{
    use ApiResponse;

    public function __invoke(Product $product): JsonResponse
    {
        $storeComparison = DB::table('shopping_items as si')
            ->join('shopping_records as sr', 'si.shopping_record_id', '=', 'sr.id')
            ->join('stores as s', 'sr.store_id', '=', 's.id')
            ->select('s.name as store_name', 's.location as store_location', 'si.price', 'sr.date')
            ->where('si.product_id', $product->id)
            ->whereIn('si.id', function ($query) use ($product) {
                $query->select(DB::raw('MAX(si_inner.id)'))
                    ->from('shopping_items as si_inner')
                    ->join('shopping_records as sr_inner', 'si_inner.shopping_record_id', '=', 'sr_inner.id')
                    ->whereColumn('sr_inner.store_id', 's.id')
                    ->where('si_inner.product_id', DB::raw($product->id))
                    ->groupBy('sr_inner.store_id');
            })
            ->orderBy('sr.date', 'desc')
            ->get();

        return $this->successResponse($storeComparison);
    }
}