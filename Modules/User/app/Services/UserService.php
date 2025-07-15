<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Modules\User\Models\User;
use Modules\User\Repositories\UserRepositoryInterface;

class UserService
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPharmacists()
    {
        return $this->userRepository->getPharmacists();
    }

    public function getSuppliers()
    {
        return $this->userRepository->getSuppliers();
    }

    public function getFilteredSuppliersByNamesOrWorkplace(array $filters = [])
    {
        $query = User::role('مورد');
        $searchTerm = $filters['search_term'] ?? null;

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%');

                $q->orWhere('workplace_name', 'like', '%'.$searchTerm.'%');
            });
        }

        return $query->get();
    }

    public function getFilteredPharmacistsByNamesOrWorkplace(array $filters = [])
    {
        $query = User::role('صيدلي');
        $searchTerm = $filters['search_term'] ?? null;

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', '%'.$searchTerm.'%');

                $q->orWhere('workplace_name', 'like', '%'.$searchTerm.'%');

            });
        }

        return $query->get();
    }

    public function registerUser(array $data, $image = null)
    {
        return DB::transaction(function () use ($data) {
            // Manually hash the password (instead of relying on mutator)
            $data['password'] = Hash::make($data['password']);

            // Create the user using the repository
            return $this->userRepository->create($data);
        });
    }

    public function getUserById(int $id): ?User
    {
        return $this->userRepository->findById($id);
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            return $this->userRepository->update($user, $data);
        });
    }
}
