<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepository implements UserRepositoryInterface
{
    public function getPharmacists()
    {
        return User::role('صيدلي')->paginate(5);
    }

    public function getSuppliers()
    {
        return User::role('مورد')->paginate(5);
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

    public function getSupplierMedicines(int $supplierId)
    {
        $user = User::findOrFail($supplierId);

        return User::find($supplierId)->medicines()
            ->select('medicines.id', 'medicines.net_syp as price', 'medicines.type as name')
            ->get();

        return response()->json($medicines);
    }

    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
