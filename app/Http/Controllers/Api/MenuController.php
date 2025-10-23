<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    /**
     * Get the vertical menu items as a flat list.
     *
     * @return JsonResponse
     */
    public function getVerticalMenu(): JsonResponse
    {
        $menuItems = Menu::where('type', 'vertical')->orderBy('id')->get();
        return response()->json($this->transformMenuItems($menuItems));
    }

    /**
     * Get the horizontal menu items as a flat list.
     *
     * @return JsonResponse
     */
    public function getHorizontalMenu(): JsonResponse
    {
        $menuItems = Menu::where('type', 'horizontal')->orderBy('id')->get();
        return response()->json($this->transformMenuItems($menuItems));
    }

    /**
     * Transform snake_case database columns to camelCase for the frontend.
     *
     * @param $items
     * @return \Illuminate\Support\Collection
     */
    private function transformMenuItems($items)
    {
        return $items->map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'routerLink' => $item->router_link,
                'href' => $item->href,
                'icon' => $item->icon,
                'target' => $item->target,
                'hasSubMenu' => (bool)$item->has_sub_menu,
                'parentId' => $item->parent_id,
            ];
        });
    }
}
