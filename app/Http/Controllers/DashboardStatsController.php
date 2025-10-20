<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ShoppingRecord;
use App\Models\Store;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DashboardStatsController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @return JsonResponse
     */
    public function __invoke(): JsonResponse
    {
        $totalRecords = ShoppingRecord::count();
        $totalConsumption = DB::table('shopping_items')->sum(DB::raw('price * quantity'));

        $lastPurchase = ShoppingRecord::latest('date')->first();

        $stats = [
            'tiles' => [
                'total_consumption' => (float) $totalConsumption,
                'total_products' => Product::count(),
                'total_records' => $totalRecords,
                'average_spending' => ($totalRecords > 0) ? (float) $totalConsumption / $totalRecords : 0,
                'total_stores' => Store::count(),
                'last_purchase_date' => $lastPurchase ? $lastPurchase->date : null,
            ],
            'charts' => [], // Placeholder for future chart data
        ];

        return response()->json(['data' => $stats]);
    }
}