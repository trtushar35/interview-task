<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{
    protected $roleModel;

    public function __construct(Role $roleModel)
    {
        $this->roleModel = $roleModel;
    }

    public function list()
    {
        return $this->roleModel->query();
    }

    public function activeList()
    {
        return $this->roleModel->where('status', 'Active')->get();
    }

    public function findById($id)
    {
        return $this->roleModel->findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            return $this->roleModel->create([
                'name' => $data['name'],
                'status' => 'Active'
            ]);
        });
    }

    public function update($id, array $data)
    {
        return DB::transaction(function () use ($id, $data) {
            $role = $this->findById($id);
            $role->update([
                'name' => $data['name']
            ]);
            return $role;
        });
    }

    public function delete($id)
    {
        return DB::transaction(function () use ($id) {
            $role = $this->findById($id);
            $role->update(['status' => 'Deleted']);
            $role->delete(); 
            return $role;
        });
    }

    public function forceDelete($id)
    {
        return DB::transaction(function () use ($id) {
            $role = $this->findById($id);
            return $role->forceDelete();
        });
    }

    public function restore($id)
    {
        return DB::transaction(function () use ($id) {
            $role = $this->roleModel->withTrashed()->findOrFail($id);
            $role->update(['status' => 'Active']);
            $role->restore();
            return $role;
        });
    }
}
