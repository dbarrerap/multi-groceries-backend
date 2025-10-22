<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PriceEstimatorController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'store_id' => 'required|integer|exists:stores,id',
            'product_ids' => 'required|array',
            'product_ids.*' => 'integer|exists:products,id',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors()->toArray(), 422);
        }

        $storeId = $request->input('store_id');
        $productIds = $request->input('product_ids');

        $latestPrices = DB::table('shopping_items as si')
            ->join('shopping_records as sr', 'si.shopping_record_id', '=', 'sr.id')
            ->select('si.product_id', 'si.price')
            ->where('sr.store_id', $storeId)
            ->whereIn('si.product_id', $productIds)
            ->whereIn('si.id', function ($query) use ($storeId) {
                $query->select(DB::raw('MAX(si_inner.id)'))
                    ->from('shopping_items as si_inner')
                    ->join('shopping_records as sr_inner', 'si_inner.shopping_record_id', '=', 'sr_inner.id')
                    ->where('sr_inner.store_id', $storeId)
                    ->whereColumn('si_inner.product_id', 'si.product_id')
                    ->groupBy('si_inner.product_id');
            })
            ->get()
            ->keyBy('product_id');

        $result = collect($productIds)->map(function ($productId) use ($latestPrices) {
            return [
                'product_id' => $productId,
                'price' => $latestPrices->get($productId)->price ?? null,
            ];
        });

        return $this->successResponse($result);
    }
}