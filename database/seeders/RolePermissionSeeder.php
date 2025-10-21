<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Get all permissions
        $permissions = DB::table('permissions')->get();
        
        // Get admin role
        $adminRole = DB::table('roles')->where('name', 'Admin')->first();
        
        if ($adminRole && $permissions->isNotEmpty()) {
            $rolePermissions = [];
            
            foreach ($permissions as $permission) {
                $rolePermissions[] = [
                    'role_id' => $adminRole->id,
                    'permission_id' => $permission->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            }
            
            // Insert all permissions for admin role
            DB::table('role_permission')->insert($rolePermissions);
        }
    }
}