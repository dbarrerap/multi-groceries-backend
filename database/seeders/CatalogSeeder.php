<?php

namespace Database\Seeders;

use App\Models\Catalog;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Product Categories
        $productCategoryRoot = Catalog::updateOrCreate(
            ['code' => 'PRODUCT_CATEGORY', 'parent_id' => null],
            ['name' => 'Product Categories']
        );

        $categories = [
            'Abarrotes',
            'Lácteos',
            'Carnes',
            'Frutas y Verduras',
            'Limpieza',
            'Higiene Personal',
        ];

        foreach ($categories as $category) {
            Catalog::updateOrCreate(
                ['name' => $category, 'parent_id' => $productCategoryRoot->id],
                ['code' => 'PRODUCT_CATEGORY_ITEM']
            );
        }

        // Units of Measure
        $productUnitRoot = Catalog::updateOrCreate(
            ['code' => 'PRODUCT_UNIT', 'parent_id' => null],
            ['name' => 'Units of Measure']
        );

        $units = [
            'Unidad',
            'Kilo',
            'Litro',
            'Gramo',
        ];

        foreach ($units as $unit) {
            Catalog::updateOrCreate(
                ['name' => $unit, 'parent_id' => $productUnitRoot->id],
                ['code' => 'PRODUCT_UNIT_ITEM']
            );
        }
    }
}
