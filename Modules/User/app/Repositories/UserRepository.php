<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getPharmacists()
    {
        return User::role('صيدلي')->get();
    }

    public function getSuppliers()
    {
        return User::role('مورد')->get();
    }

    public function create(array $data): User
    {
        $user = User::create($data);
        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
