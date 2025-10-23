<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear the table before seeding
        Menu::query()->delete();

        $verticalMenuItems = [
            [1, 'Dashboard', '/', null, 'dashboard', null, false, 0],
            [600, 'Cotizador', '/cotizador', null, 'calculate', null, false, 0],
            [300, 'Tiendas', '/stores/list', null, 'store', null, false, 0],
            [301, 'Productos', '/products', null, 'shopping_cart', null, true, 0],
            [302, 'Crear Producto', '/products/create', null, 'add_circle', null, false, 301],
            [303, 'Configuracion', '/products/settings', null, 'settings', null, false, 301],
            [304, 'Lista de Productos', '/products/list', null, 'list', null, false, 301],
            [400, 'Registro de Compra', '/shopping-records', null, 'receipt', null, true, 0],
            [401, 'Lista de Registros de Compra', '/shopping-records/list', null, 'list_alt', null, false, 400],
            [402, 'Crear Registro de Compra', '/shopping-records/create', null, 'add_shopping_cart', null, false, 400],
            [500, 'Reportes', '/reports', null, 'analytics', null, true, 0],
            [501, 'Temporal por Producto', '/analysis/temporal-product', null, 'timeline', null, false, 500],
            [502, 'Productos Más Comprados', '/reports/most-purchased', null, 'star', null, false, 500],
            [2, 'Users', '/users', null, 'supervisor_account', null, false, 0],
            // [15, 'Dynamic Menu', '/dynamic-menu', null, 'format_list_bulleted', null, false, 0],
            // [43, 'Login', '/login', null, 'exit_to_app', null, false, 40],
            // [44, 'Register', '/register', null, 'person_add', null, false, 40],
            [45, 'Blank', '/blank', null, 'check_box_outline_blank', null, false, 40],
            // [200, 'External Link', null, 'http://themeseason.com', 'open_in_new', '_blank', false, 0]
        ];

        $horizontalMenuItems = [
            [1001, 'Dashboard', '/', null, 'dashboard', null, false, 0],
            [1600, 'Cotizador', '/cotizador', null, 'calculate', null, false, 0],
            [1300, 'Tiendas', '/stores/list', null, 'store', null, false, 0],
            [1301, 'Productos', '/products', null, 'shopping_cart', null, true, 0],
            [1302, 'Crear Producto', '/products/create', null, 'add_circle', null, false, 1301],
            [1303, 'Configuracion', '/products/settings', null, 'settings', null, false, 1301],
            [1304, 'Lista de Productos', '/products/list', null, 'list', null, false, 1301],
            [1400, 'Registro de Compra', '/shopping-records', null, 'receipt', null, true, 0],
            [1401, 'Lista de Registros de Compra', '/shopping-records/list', null, 'list_alt', null, false, 1400],
            [1402, 'Crear Registro de Compra', '/shopping-records/create', null, 'add_shopping_cart', null, false, 1400],
            [1500, 'Reportes', '/reports', null, 'analytics', null, true, 0],
            [1501, 'Temporal por Producto', '/analysis/temporal-product', null, 'timeline', null, false, 1500],
            [1502, 'Productos Más Comprados', '/reports/most-purchased', null, 'star', null, false, 1500],
        ];

        foreach ($verticalMenuItems as $item) {
            Menu::create([
                'id' => $item[0],
                'title' => $item[1],
                'router_link' => $item[2],
                'href' => $item[3],
                'icon' => $item[4],
                'target' => $item[5],
                'has_sub_menu' => $item[6],
                'parent_id' => $item[7],
                'type' => 'vertical'
            ]);
        }

        foreach ($horizontalMenuItems as $item) {
            Menu::create([
                'id' => $item[0],
                'title' => $item[1],
                'router_link' => $item[2],
                'href' => $item[3],
                'icon' => $item[4],
                'target' => $item[5],
                'has_sub_menu' => $item[6],
                'parent_id' => $item[7],
                'type' => 'horizontal'
            ]);
        }
    }
}