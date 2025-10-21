<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserService
{
     protected $userModel;

    public function __construct(User $userModel)
    {
        $this->userModel = $userModel;
    }

    public function list()
    {
        return $this->userModel->with('role')->whereNull('deleted_at');
    }

    public function all()
    {
        return $this->userModel->whereNull('deleted_at')->get();
    }

    public function find($id)
    {
        return $this->userModel->with('role')->find($id);
    }

    public function create(array $data)
    {
        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        }
        
        return $this->userModel->create($data);
    }

    public function update($id, array $data)
    {
        $dataInfo = $this->userModel->findOrFail($id);

        // Hash password if provided
        if (isset($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        $dataInfo->update($data);

        return $dataInfo;
    }

    public function delete($id)
    {
        $dataInfo = $this->userModel->find($id);

        if (!empty($dataInfo)) {
            // Delete photo if exists
            if (!empty($dataInfo->photo) && Storage::disk('public')->exists($dataInfo->photo)) {
                Storage::disk('public')->delete($dataInfo->photo);
            }

            $dataInfo->deleted_at = date('Y-m-d H:i:s');
            $dataInfo->status = 'Deleted';
            $dataInfo->save(); 

            return $dataInfo;
        }
        return false;
    }

    public function changeStatus($request)
    {
        $dataInfo = $this->userModel->findOrFail($request->id);

        $dataInfo->update(
            [
                'status' => $request->status,
            ]
        );

        return $dataInfo;
    }

    public function activeList()
    {
        return $this->userModel->with('role')->whereNull('deleted_at')->where('status', 'Active')->get();
    }

    public function userExists($userName)
    {
        return $this->userModel->whereNull('deleted_at')
            ->where(function ($q) use ($userName) {
                $q->where('email', strtolower($userName));
            })->first();
    }
}