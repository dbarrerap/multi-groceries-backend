<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ShoppingItem;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class StoreComparisonController extends Controller
{
    /**
     * Display the latest price of a product across different stores.
     */
    public function show(Product $product): Response
    {
        $storeComparison = [];

        $shoppingItems = $product->shoppingItems()
            ->with(['shoppingRecord.store'])
            ->orderByDesc(
                ShoppingItem::select('date')
                    ->from('shopping_records')
                    ->whereColumn('shopping_records.id', 'shopping_items.shopping_record_id')
                    ->limit(1)
            )
            ->get();

        $latestPrices = [];

        foreach ($shoppingItems as $item) {
            $storeId = $item->shoppingRecord->store->id;
            $recordDate = $item->shoppingRecord->date;

            if (!isset($latestPrices[$storeId]) || $recordDate > $latestPrices[$storeId]['date']) {
                $latestPrices[$storeId] = [
                    'store_name' => $item->shoppingRecord->store->name,
                    'price' => $item->price,
                    'date' => $recordDate,
                ];
            }
        }

        // Convert associative array to indexed array for consistent JSON output
        $storeComparison = array_values($latestPrices);

        return response($storeComparison);
    }
}
