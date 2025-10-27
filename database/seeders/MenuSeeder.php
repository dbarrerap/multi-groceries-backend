<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $menuItems = [
            // Vertical Menu Items (Originales)
            ['id' => 1, 'title' => 'Dashboard', 'router_link' => '/', 'icon' => 'dashboard', 'type' => 'vertical', 'parent_id' => 0, 'role' => null],
            ['id' => 600, 'title' => 'Cotizador', 'router_link' => '/cotizador', 'icon' => 'calculate', 'type' => 'vertical', 'parent_id' => 0, 'role' => null],
            ['id' => 300, 'title' => 'Tiendas', 'router_link' => '/stores/list', 'icon' => 'store', 'type' => 'vertical', 'parent_id' => 0, 'role' => null],
            ['id' => 301, 'title' => 'Productos', 'router_link' => '/products', 'icon' => 'shopping_cart', 'type' => 'vertical', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 302, 'title' => 'Crear Producto', 'router_link' => '/products/create', 'icon' => 'add_circle', 'type' => 'vertical', 'parent_id' => 301, 'role' => null],
            ['id' => 303, 'title' => 'Configuracion', 'router_link' => '/products/settings', 'icon' => 'settings', 'type' => 'vertical', 'parent_id' => 301, 'role' => null],
            ['id' => 304, 'title' => 'Lista de Productos', 'router_link' => '/products/list', 'icon' => 'list', 'type' => 'vertical', 'parent_id' => 301, 'role' => null],
            ['id' => 400, 'title' => 'Registro de Compra', 'router_link' => '/shopping-records', 'icon' => 'receipt', 'type' => 'vertical', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 401, 'title' => 'Lista de Registros de Compra', 'router_link' => '/shopping-records/list', 'icon' => 'list_alt', 'type' => 'vertical', 'parent_id' => 400, 'role' => null],
            ['id' => 402, 'title' => 'Crear Registro de Compra', 'router_link' => '/shopping-records/create', 'icon' => 'add_shopping_cart', 'type' => 'vertical', 'parent_id' => 400, 'role' => null],
            ['id' => 500, 'title' => 'Reportes', 'router_link' => '/reports', 'icon' => 'analytics', 'type' => 'vertical', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 501, 'title' => 'Temporal por Producto', 'router_link' => '/analysis/temporal-product', 'icon' => 'timeline', 'type' => 'vertical', 'parent_id' => 500, 'role' => null],
            ['id' => 502, 'title' => 'Productos Más Comprados', 'router_link' => '/reports/most-purchased', 'icon' => 'star', 'type' => 'vertical', 'parent_id' => 500, 'role' => null],
            ['id' => 2, 'title' => 'Users', 'router_link' => '/users', 'icon' => 'supervisor_account', 'type' => 'vertical', 'parent_id' => 0, 'role' => null],
            ['id' => 45, 'title' => 'Blank', 'router_link' => '/blank', 'icon' => 'check_box_outline_blank', 'type' => 'vertical', 'parent_id' => 0, 'role' => null],

            // NUEVOS ITEMS DE ADMIN (Vertical)
            ['id' => 700, 'title' => 'Panel de Control', 'router_link' => null, 'icon' => 'admin_panel_settings', 'type' => 'vertical', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => 'Admin'],
            ['id' => 701, 'title' => 'Usuarios', 'router_link' => '/admin/users', 'icon' => 'supervisor_account', 'type' => 'vertical', 'parent_id' => 700, 'role' => 'Admin'],

            // Horizontal Menu Items (Originales)
            ['id' => 1001, 'title' => 'Dashboard', 'router_link' => '/', 'icon' => 'dashboard', 'type' => 'horizontal', 'parent_id' => 0, 'role' => null],
            ['id' => 1600, 'title' => 'Cotizador', 'router_link' => '/cotizador', 'icon' => 'calculate', 'type' => 'horizontal', 'parent_id' => 0, 'role' => null],
            ['id' => 1300, 'title' => 'Tiendas', 'router_link' => '/stores/list', 'icon' => 'store', 'type' => 'horizontal', 'parent_id' => 0, 'role' => null],
            ['id' => 1301, 'title' => 'Productos', 'router_link' => '/products', 'icon' => 'shopping_cart', 'type' => 'horizontal', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 1302, 'title' => 'Crear Producto', 'router_link' => '/products/create', 'icon' => 'add_circle', 'type' => 'horizontal', 'parent_id' => 1301, 'role' => null],
            ['id' => 1303, 'title' => 'Configuracion', 'router_link' => '/products/settings', 'icon' => 'settings', 'type' => 'horizontal', 'parent_id' => 1301, 'role' => null],
            ['id' => 1304, 'title' => 'Lista de Productos', 'router_link' => '/products/list', 'icon' => 'list', 'type' => 'horizontal', 'parent_id' => 1301, 'role' => null],
            ['id' => 1400, 'title' => 'Registro de Compra', 'router_link' => '/shopping-records', 'icon' => 'receipt', 'type' => 'horizontal', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 1401, 'title' => 'Lista de Registros de Compra', 'router_link' => '/shopping-records/list', 'icon' => 'list_alt', 'type' => 'horizontal', 'parent_id' => 1400, 'role' => null],
            ['id' => 1402, 'title' => 'Crear Registro de Compra', 'router_link' => '/shopping-records/create', 'icon' => 'add_shopping_cart', 'type' => 'horizontal', 'parent_id' => 1400, 'role' => null],
            ['id' => 1500, 'title' => 'Reportes', 'router_link' => '/reports', 'icon' => 'analytics', 'type' => 'horizontal', 'has_sub_menu' => true, 'parent_id' => 0, 'role' => null],
            ['id' => 1501, 'title' => 'Temporal por Producto', 'router_link' => '/analysis/temporal-product', 'icon' => 'timeline', 'type' => 'horizontal', 'parent_id' => 1500, 'role' => null],
            ['id' => 1502, 'title' => 'Productos Más Comprados', 'router_link' => '/reports/most-purchased', 'icon' => 'star', 'type' => 'horizontal', 'parent_id' => 1500, 'role' => null],
        ];

        foreach ($menuItems as $item) {
            Menu::updateOrCreate(
                ['id' => $item['id']],
                [
                    'title' => $item['title'],
                    'router_link' => $item['router_link'],
                    'icon' => $item['icon'],
                    'type' => $item['type'],
                    'parent_id' => $item['parent_id'],
                    'role' => $item['role'],
                    'has_sub_menu' => $item['has_sub_menu'] ?? false,
                    'href' => $item['href'] ?? null,
                    'target' => $item['target'] ?? null,
                ]
            );
        }
    }
}
