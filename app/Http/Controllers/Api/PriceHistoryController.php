<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use App\Models\Product;
use App\Models\ShoppingRecord;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;

class PriceHistoryController extends Controller
{
    use ApiResponse;
    /**
     * Display the price history for a specific product.
     */
    public function __invoke(Product $product)
    {
        // 1. Obtener los datos planos de la base de datos
        $rawData = $product->shoppingItems()
            ->join('shopping_records', 'shopping_items.shopping_record_id', '=', 'shopping_records.id')
            ->join('stores', 'shopping_records.store_id', '=', 'stores.id')
            ->orderBy('shopping_records.date', 'asc')
            ->get([
                'shopping_records.date as date',
                'shopping_items.price as price',
                'stores.name as store_name'
            ]);

        // 2. Agrupar los datos por tienda usando colecciones de Laravel
        $groupedData = $rawData->groupBy('store_name');

        // 3. Transformar los datos agrupados al formato de ngx-charts
        $formattedData = $groupedData->map(function ($items, $storeName) {
            return [
                'name' => $storeName,
                'series' => $items->map(function ($item) {
                    return [
                        'name' => $item->date, // ngx-charts usa 'name' para el eje X
                        'value' => $item->price // ngx-charts usa 'value' para el eje Y
                    ];
                })->values() // Usamos values() para resetear las keys del array
            ];
        })->values(); // Usamos values() para asegurar un array JSON, no un objeto

        return $this->successResponse($formattedData);
    }
}
