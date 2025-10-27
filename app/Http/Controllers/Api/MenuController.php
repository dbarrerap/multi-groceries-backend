<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    /**
     * Get the vertical menu items as a flat list.
     *
     * @return JsonResponse
     */
    public function getVerticalMenu(): JsonResponse
    {
        return $this->getFilteredMenuForType('vertical');
    }

    /**
     * Get the horizontal menu items as a flat list.
     *
     * @return JsonResponse
     */
    public function getHorizontalMenu(): JsonResponse
    {
        return $this->getFilteredMenuForType('horizontal');
    }

    /**
     * Get menu items for a given type, filtered by the authenticated user's role.
     *
     * @param string $type
     * @return JsonResponse
     */
    private function getFilteredMenuForType(string $type): JsonResponse
    {
        $user = Auth::user();
        $menuItems = Menu::where('type', $type)->orderBy('id')->get();

        $filteredMenuItems = $menuItems->filter(function ($item) use ($user) {
            // 1. If item has no role, it's public for everyone
            if (is_null($item->role)) {
                return true;
            }

            // 2. If user is not logged in, they can't see role-protected items
            if (!$user) {
                return false;
            }

            // 3. If user is Super-Admin, show everything
            if ($user->hasRole('Super-Admin')) {
                return true;
            }

            // 4. Show item if the user has the required role
            return $user->hasRole($item->role);
        });

        return response()->json($this->transformMenuItems($filteredMenuItems->values()));
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