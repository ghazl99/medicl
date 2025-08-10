<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

class UserRepository implements UserRepositoryInterface
{
    // Get pharmacists with optional keyword search including cities
    public function getPharmacists(?string $keyword = null)
    {
        $query = User::role('صيدلي')->with('cities'); // eager load cities always

        if ($keyword) {
            // Search using Scout, then filter users by the search result IDs
            $searchedUserIds = User::search($keyword)
                ->query(function ($query) use ($keyword) {
                    // Also filter by city name containing keyword
                    $query->orWhereHas('cities', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->keys();
            $query->whereIn('id', $searchedUserIds);
        }

        return $query->paginate(10);
    }

    // Get suppliers with optional keyword search including cities
    public function getSuppliers(?string $keyword = null)
    {
        $query = User::role('مورد')->with('cities'); // eager load cities

        if ($keyword) {
            // Search with Scout and filter by city names
            $searchedUserIds = User::search($keyword)
                ->query(function ($query) use ($keyword) {
                    $query->orWhereHas('cities', function ($q) use ($keyword) {
                        $q->where('name', 'like', "%{$keyword}%");
                    });
                })
                ->keys();
            $query->whereIn('id', $searchedUserIds);
        }

        return $query->paginate(10);
    }

    // Create a new user and assign role if provided
    public function create(array $data): User
    {
        $user = User::create($data);
        if (isset($data['role'])) {
            $user->assignRole($data['role']);
        }

        return $user;
    }

    // Find a user by their ID
    public function findById(int $id): ?User
    {
        return User::find($id);
    }

    // Get medicines associated with a supplier by supplier ID
    public function getSupplierMedicines(int $supplierId)
    {
        $user = User::findOrFail($supplierId);

        return User::find($supplierId)->medicines()
            ->select('medicines.id', 'medicines.net_syp as price', 'medicines.type as name')
            ->get();

        // Note: unreachable code after return statement below
        return response()->json($medicines);
    }

    // Update user data and save changes
    public function update(User $user, array $data): User
    {
        $user->fill($data);
        $user->save();

        return $user;
    }
}
