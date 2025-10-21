<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\UserService;
use App\Services\RoleService;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class UserController extends Controller
{
    protected $userService;
    protected $roleService;

    public function __construct(UserService $userService, RoleService $roleService)
    {
        $this->userService = $userService;
        $this->roleService = $roleService;
    }

    public function index()
    {
        return Inertia::render(
            'Backend/User/Index',
            [
                'pageTitle' => fn() => 'User List',
                'breadcrumbs' => fn() => [
                    ['link' => null, 'title' => 'User Manage'],
                    ['link' => route('backend.user.index'), 'title' => 'User List'],
                ],
                'tableHeaders' => fn() => $this->getTableHeaders(),
                'dataFields' => fn() => $this->dataFields(),
                'datas' => fn() => $this->getDatas(),
                'filters' => request()->only(['numOfData', 'name', 'email', 'status']),
            ]
        );
    }

    private function getDatas()
    {
        $query = $this->userService->list();

        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request()->name . '%');
        }

        if (request()->filled('email')) {
            $query->where('email', 'like', '%' . request()->email . '%');
        }

        if (request()->filled('status') && request()->status !== 'All') {
            $query->where('status', request()->status);
        }

        $datas = $query->paginate(request()->numOfData ?? 10)->withQueryString();

        $formatedDatas = $datas->map(function ($data, $index) {
            $customData = new \stdClass();
            $customData->index = $index + 1;
            $customData->name = $data->name;
            $customData->email = $data->email;
            $customData->number = $data->number ?? 'N/A';
            $customData->role = $data->role->name ?? 'N/A';
            $customData->status = $data->status;

            $customData->photo = $data->photo_url;

            $customData->hasLink = true;
            $customData->links = [
                [
                    'linkClass' => 'bg-yellow-400 text-black semi-bold',
                    'link' => route('backend.user.edit', $data->id),
                    'linkLabel' => getLinkLabel('Edit', null, null)
                ],
                [
                    'linkClass' => 'deleteButton bg-red-500 text-white semi-bold',
                    'link' => route('backend.user.destroy', $data->id),
                    'linkLabel' => getLinkLabel('Delete', null, null)
                ]
            ];
            return $customData;
        });

        return regeneratePagination($formatedDatas, $datas->total(), $datas->perPage(), $datas->currentPage());
    }

    private function dataFields()
    {
        return [
            ['fieldName' => 'index', 'class' => 'text-center'],
            ['fieldName' => 'photo', 'class' => 'text-center'],
            ['fieldName' => 'name', 'class' => 'text-center'],
            ['fieldName' => 'email', 'class' => 'text-center'],
            ['fieldName' => 'number', 'class' => 'text-center'],
            ['fieldName' => 'role', 'class' => 'text-center'],
            ['fieldName' => 'status', 'class' => 'text-center'],
        ];
    }

    private function getTableHeaders()
    {
        return [
            'Sl/No',
            'Photo',
            'Name',
            'Email',
            'Phone Number',
            'Role',
            'Status',
            'Action'
        ];
    }

    public function create()
    {
        $roles = $this->roleService->list()->where('status', 'Active')->get(['id', 'name']);

        return Inertia::render(
            'Backend/User/Form',
            [
                'pageTitle' => fn() => 'Create User',
                'breadcrumbs' => fn() => [
                    ['link' => null, 'title' => 'User Manage'],
                    ['link' => route('backend.user.create'), 'title' => 'Create User'],
                ],
                'roles' => $roles,
            ]
        );
    }

    public function store(UserRequest $request)
    {
        try {
            $data = $request->validated();

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $data['photo'] = $this->imageUpload($request->file('photo'), 'users');
            }

            // Handle password - only if provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $dataInfo = $this->userService->create($data);

            return redirect()->route('backend.user.index')
                ->with('success', 'User created successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('errorMessage', 'Failed to create user: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        try {
            $user = $this->userService->find($id);
            $roles = $this->roleService->list()->where('status', 'Active')->get(['id', 'name']);

            return Inertia::render(
                'Backend/User/Form',
                [
                    'pageTitle' => fn() => 'Edit User',
                    'breadcrumbs' => fn() => [
                        ['link' => null, 'title' => 'User Manage'],
                        ['link' => route('backend.user.edit', $id), 'title' => 'Edit User'],
                    ],
                    'user' => $user,
                    'roles' => $roles,
                    'id' => $id
                ]
            );
        } catch (\Exception $e) {
            return redirect()->route('backend.user.index')
                ->with('errorMessage', 'User not found!');
        }
    }

    public function update(UserRequest $request, string $id)
    {
        try {
            $data = $request->validated();

            $user = $this->userService->find($id);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $data['photo'] = $this->imageUpload($request->file('photo'), 'users');
            } else {
                // If no new photo, keep the existing one
                unset($data['photo']);
            }

            // Handle password - only update if provided
            if (empty($data['password'])) {
                unset($data['password']);
            }

            $this->userService->update($id, $data);

            return redirect()->route('backend.user.index')
                ->with('success', 'User updated successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('errorMessage', 'Failed to update user: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $user = $this->userService->find($id);

            if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                Storage::disk('public')->delete($user->photo);
            }

            $this->userService->delete($id);

            return redirect()->route('backend.user.index')
                ->with('success', 'User deleted successfully!');
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('errorMessage', 'Failed to delete user: ' . $e->getMessage());
        }
    }

    private function imageUpload($image, $folder = 'users')
    {
        $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
        $imagePath = $image->storeAs($folder, $imageName, 'public');
        return $imagePath;
    }
}
