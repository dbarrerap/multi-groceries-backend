<?php

namespace App\Http\Controllers\Api\Reports;

use App\Http\Controllers\Controller;
use App\Http\Traits\ApiResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MostPurchasedProductsController extends Controller
{
    use ApiResponse;

    public function __invoke(Request $request)
    {
        $query = "
            WITH LastPurchases AS (
                SELECT
                    si.product_id,
                    si.price,
                    s.name AS store_name,
                    ROW_NUMBER() OVER(PARTITION BY si.product_id ORDER BY sr.date DESC, sr.id DESC) as rn
                FROM
                    shopping_items si
                JOIN
                    shopping_records sr ON si.shopping_record_id = sr.id
                JOIN
                    stores s ON sr.store_id = s.id
            ),
            PurchaseCounts AS (
                SELECT
                    product_id,
                    COUNT(product_id) as times_purchased
                FROM
                    shopping_items
                GROUP BY
                    product_id
            )
            SELECT
                p.id AS product_id,
                p.name AS product_name,
                p.brand AS product_brand,
                pc.times_purchased,
                lp.price AS last_price,
                lp.store_name AS last_store_name
            FROM
                PurchaseCounts pc
            JOIN
                products p ON pc.product_id = p.id
            JOIN
                LastPurchases lp ON pc.product_id = lp.product_id
            WHERE
                lp.rn = 1
            ORDER BY
                pc.times_purchased DESC
            LIMIT 15;
        ";

        $mostPurchased = DB::select($query);

        return $this->successResponse($mostPurchased);
    }
}