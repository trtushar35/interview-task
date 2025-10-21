<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run()
    {
        foreach ($this->datas() as $key => $value) {
            $this->createMenu($value);
        }
    }

    private function createMenu($data, $parent_id = null)
    {
        $menu = new Menu([
            'name' => $data['name'],
            'icon' => $data['icon'],
            'route' => $data['route'],
            'description' => $data['description'],
            'sorting' => $data['sorting'],
            'parent_id' => $parent_id,
            'permission_name' => $data['permission_name'],
            'status' => $data['status'],
            'created_at' => now(),
            'updated_at' => now(),
            'deleted_at' => null,
        ]);

        $menu->save();

        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createMenu($child, $menu->id);
            }
        }
    }

    private function datas()
    {
        return [
            [
                'name' => 'Dashboard',
                'icon' => '',
                'route' => null,
                'description' => null,
                'sorting' => 1,
                'permission_name' => 'dashboard-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'Overview',
                        'icon' => 'list',
                        'route' => '',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'overview-dashboard',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'Analytics',
                        'icon' => 'list',
                        'route' => '',
                        'description' => null,
                        'sorting' => 2,
                        'permission_name' => 'analytics-dashboard',
                        'status' => 'Active',
                    ],
                    [
                        'name' => 'Reports',
                        'icon' => 'list',
                        'route' => '',
                        'description' => null,
                        'sorting' => 3,
                        'permission_name' => 'reports-dashboard',
                        'status' => 'Active',
                    ],
                ],
            ],
            
            [
                'name' => 'Role Management',
                'icon' => '',
                'route' => null,
                'description' => null,
                'sorting' => 2,
                'permission_name' => 'role-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'Role List',
                        'icon' => 'list',
                        'route' => '',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'role-list',
                        'status' => 'Active',
                    ],
                ],
            ],
            
            [
                'name' => 'User Management',
                'icon' => '',
                'route' => null,
                'description' => null,
                'sorting' => 2,
                'permission_name' => 'user-management',
                'status' => 'Active',
                'children' => [
                    [
                        'name' => 'User List',
                        'icon' => 'list',
                        'route' => '',
                        'description' => null,
                        'sorting' => 1,
                        'permission_name' => 'user-list',
                        'status' => 'Active',
                    ],
                ],
            ],
            
        ];
    }
}
