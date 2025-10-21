<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        foreach ($this->datas() as $key => $value) {
            $this->createPermission($value);
        }
    }

    private function createPermission($data, $parent_id = null)
    {
        $permission = new Permission([
            'name' => $data['name'],
            'parent_id' => $parent_id,
            'status' => 'Active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $permission->save();

        if (isset($data['children']) && is_array($data['children'])) {
            foreach ($data['children'] as $child) {
                $this->createPermission($child, $permission->id);
            }
        }
    }

    private function datas()
    {
        return [
            // Dashboard Management Permissions
            [
                'name' => 'dashboard-management',
                'children' => [
                    ['name' => 'overview-dashboard'],
                    ['name' => 'analytics-dashboard'],
                    ['name' => 'reports-dashboard'],
                ],
            ],

            // Role Management Permissions
            [
                'name' => 'role-management',
                'children' => [
                    [
                        'name' => 'role-list',
                        'children' => [
                            ['name' => 'role-view'],
                            ['name' => 'role-create'],
                            ['name' => 'role-edit'],
                            ['name' => 'role-update'],
                            ['name' => 'role-delete'],
                        ]
                    ],
                ],
            ],

            // User Management Permissions
            [
                'name' => 'user-management',
                'children' => [
                    [
                        'name' => 'user-list',
                        'children' => [
                            ['name' => 'user-view'],
                            ['name' => 'user-create'],
                            ['name' => 'user-edit'],
                            ['name' => 'user-update'],
                            ['name' => 'user-delete'],
                        ]
                    ],
                ],
            ],
        ];
    }
}