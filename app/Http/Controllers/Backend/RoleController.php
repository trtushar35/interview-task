<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Services\RoleService;
use App\Http\Requests\RoleRequest;
use Illuminate\Http\Request;
use Inertia\Inertia;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index()
    {
        return Inertia::render(
            'Backend/Role/Index',
            [
                'pageTitle' => fn () => 'Role List',
                'breadcrumbs' => fn () => [
                    ['link' => null, 'title' => 'Role Manage'],
                    ['link' => route('backend.role.index'), 'title' => 'Role List'],
                ],
                'tableHeaders' => fn () => $this->getTableHeaders(),
                'dataFields' => fn () => $this->dataFields(),
                'datas' => fn () => $this->getDatas(),
                'filters' => request()->only(['numOfData', 'name']),
            ]
        );
    }

    private function getDatas()
    {
        $query = $this->roleService->list();

        if (request()->filled('name')) {
            $query->where('name', 'like', '%' . request()->name . '%');
        }

        $datas = $query->paginate(request()->numOfData ?? 10)->withQueryString();

        $formatedDatas = $datas->map(function ($data, $index) {
            $customData = new \stdClass();
            $customData->index = $index + 1;
            $customData->role_name = $data->name;
            $customData->status = $data->status;

            $customData->hasLink = true;
            $customData->links = [
                [
                    'linkClass' => 'bg-yellow-400 text-black semi-bold',
                    'link' => route('backend.role.edit', $data->id),
                    'linkLabel' => getLinkLabel('Edit', null, null)
                ],
                [
                    'linkClass' => 'deleteButton bg-red-500 text-white semi-bold',
                    'link' => route('backend.role.destroy', $data->id),
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
            ['fieldName' => 'role_name', 'class' => 'text-center'],
            ['fieldName' => 'status', 'class' => 'text-center'],
        ];
    }

    private function getTableHeaders()
    {
        return [
            'Sl/No',
            'Role Name',
            'Status',
            'Action'
        ];
    }

    public function create()
    {
        return Inertia::render(
            'Backend/Role/Form',
            [
                'pageTitle' => fn () => 'Create Role',
                'breadcrumbs' => fn () => [
                    ['link' => null, 'title' => 'Role Manage'],
                    ['link' => route('backend.role.create'), 'title' => 'Create Role'],
                ],
            ]
        );
    }

    public function store(RoleRequest $request)
    {
        try {
            $this->roleService->store($request->validated());
            
            return redirect()->route('backend.role.index')
                ->with('success', 'Role created successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('errorMessage', 'Failed to create role: ' . $e->getMessage());
        }
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        try {
            $role = $this->roleService->findById($id);
            
            return Inertia::render(
                'Backend/Role/Form',
                [
                    'pageTitle' => fn () => 'Edit Role',
                    'breadcrumbs' => fn () => [
                        ['link' => null, 'title' => 'Role Manage'],
                        ['link' => route('backend.role.edit', $id), 'title' => 'Edit Role'],
                    ],
                    'role' => $role,
                    'id' => $id
                ]
            );
        } catch (\Exception $e) {
            return redirect()->route('backend.role.index')
                ->with('errorMessage', 'Role not found!');
        }
    }

    public function update(RoleRequest $request, string $id)
    {
        try {
            $this->roleService->update($id, $request->validated());
            
            return redirect()->route('backend.role.index')
                ->with('success', 'Role updated successfully!');
                
        } catch (\Exception $e) {
            return redirect()->back()
                ->with('errorMessage', 'Failed to update role: ' . $e->getMessage());
        }
    }

    public function destroy(string $id)
    {
        try {
            $this->roleService->forceDelete($id);
            
            return back()->with('success', 'Role deleted successfully!');
                
        } catch (\Exception $e) {

            return redirect()->back()
                ->with('errorMessage', 'Failed to delete role: ' . $e->getMessage());
        }
    }
}