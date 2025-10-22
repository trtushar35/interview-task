<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use App\Services\UserService;
use App\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        try {
            $query = $this->userService->list();

            if ($request->filled('name')) {
                $query->where('name', 'like', '%' . $request->name . '%');
            }

            if ($request->filled('email')) {
                $query->where('email', 'like', '%' . $request->email . '%');
            }

            if ($request->filled('status') && $request->status !== 'All') {
                $query->where('status', $request->status);
            }

            $users = $query->paginate($request->numOfData ?? 10)->withQueryString();

            return response()->json([
                'status' => 'success',
                'message' => 'Users retrieved successfully.',
                'data' => $users,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve users: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Store a newly created user.
     */
    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->imageUpload($request->file('photo'), 'users');
            }

            // Skip password if not provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $user = $this->userService->create($data);

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully!',
                'data' => $user,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Display the specified user.
     */
    public function show($id)
    {
        try {
            $user = $this->userService->find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'User details retrieved successfully.',
                'data' => $user,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to fetch user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Update the specified user.
     */
    public function update(UserRequest $request, $id)
    {
        try {
            $data = $request->validated();
            $user = $this->userService->find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            // Handle photo upload
            if ($request->hasFile('photo')) {
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $data['photo'] = $this->imageUpload($request->file('photo'), 'users');
            } else {
                unset($data['photo']);
            }

            // Handle password update
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $updatedUser = $this->userService->update($id, $data);

            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully!',
                'data' => $updatedUser,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy($id)
    {
        try {
            $user = $this->userService->find($id);

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found.',
                ], 404);
            }

            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $this->userService->delete($id);

            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully!',
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete user: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get all active roles for dropdown selection.
     */
    public function roles()
    {
        try {
            $roles = $this->roleService->list()
                ->where('status', 'Active')
                ->get(['id', 'name']);

            return response()->json([
                'status' => 'success',
                'message' => 'Roles retrieved successfully.',
                'data' => $roles,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve roles: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Handle image upload.
     */
    private function imageUpload($image, $folder = 'users')
    {
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        return $image->storeAs($folder, $imageName, 'public');
    }
}
