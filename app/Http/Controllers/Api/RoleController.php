<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoleRequest;
use App\Services\RoleService;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of roles.
     */
    public function index(Request $request)
    {
        try {
            $query = $this->roleService->list();

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            $roles = $query->paginate($request->numOfData ?? 10)->withQueryString();

            return response()->json([
                'status' => 'success',
                'message' => 'Roles retrieved successfully.',
                'data' => $roles
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch roles: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created role.
     */
    public function store(RoleRequest $request)
    {
        try {
            $role = $this->roleService->store($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Role created successfully!',
                'data' => $role
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified role.
     */
    public function show($id)
    {
        try {
            $role = $this->roleService->findById($id);

            if (!$role) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Role not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Role details retrieved successfully.',
                'data' => $role
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch role details: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified role.
     */
    public function update(RoleRequest $request, $id)
    {
        try {
            $updatedRole = $this->roleService->update($id, $request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'Role updated successfully!',
                'data' => $updatedRole
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified role.
     */
    public function destroy($id)
    {
        try {
            $this->roleService->forceDelete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Role deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete role: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get available permissions (for create/edit forms).
     */
    public function permissions()
    {
        try {
            $permissions = $this->roleService->getPermissionsWithHierarchy();

            return response()->json([
                'status' => 'success',
                'message' => 'Permissions retrieved successfully.',
                'data' => $permissions
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch permissions: ' . $e->getMessage()
            ], 500);
        }
    }
}
