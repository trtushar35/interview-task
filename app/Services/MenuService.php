<?php

namespace App\Services;

use App\Models\Menu;
use App\Models\User;

class MenuService
{
    /**
     * Get menus filtered by user permissions
     * 
     * @param User $user
     * @return array
     */
    public function getUserMenus(User $user): array
    {
        // Admin gets all menus
        if ($user->role->name === 'Admin') {
            return Menu::active()
                ->parent()
                ->ordered()
                ->with(['children' => function ($query) {
                    $query->active()->ordered();
                }])
                ->get()
                ->toArray();
        }

        // Get all parent menus
        $parentMenus = Menu::active()
            ->parent()
            ->ordered()
            ->get();

        $filteredMenus = [];

        foreach ($parentMenus as $parentMenu) {
            // Check if user has permission for parent menu
            $hasParentPermission = $this->userHasPermission($user, $parentMenu->permission_name);

            if (!$hasParentPermission) {
                continue;
            }

            // Get children and filter by permissions
            $children = Menu::where('parent_id', $parentMenu->id)
                ->active()
                ->ordered()
                ->get();

            $filteredChildren = [];

            foreach ($children as $child) {
                // Check if user has permission for child menu
                if ($this->userHasPermission($user, $child->permission_name)) {
                    $filteredChildren[] = [
                        'id' => $child->id,
                        'name' => $child->name,
                        'icon' => $child->icon,
                        'route' => $child->route,
                        'href' => $child->href,
                        'permission_name' => $child->permission_name,
                        'status' => $child->status,
                    ];
                }
            }

            // Only add parent menu if it has accessible children
            if (count($filteredChildren) > 0) {
                $filteredMenus[] = [
                    'id' => $parentMenu->id,
                    'name' => $parentMenu->name,
                    'icon' => $parentMenu->icon,
                    'route' => $parentMenu->route,
                    'href' => $parentMenu->href,
                    'permission_name' => $parentMenu->permission_name,
                    'status' => $parentMenu->status,
                    'children' => $filteredChildren,
                ];
            }
        }

        return $filteredMenus;
    }

    private function userHasPermission(User $user, ?string $permissionName): bool
    {
        // If no permission required, allow access
        if (empty($permissionName)) {
            return true;
        }

        // Check if user has the specific permission
        return $user->hasPermission($permissionName);
    }
}
