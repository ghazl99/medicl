<?php

namespace Modules\User\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

    public function getMedicinesBySupplier(int $supplierId)
    {
        return $this->userRepository->getSupplierMedicines($supplierId);
    }

    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            if (isset($data['profile_photo'])) {
                if ($user->profile_photo) {
                    Storage::disk('public')->delete('profile_photos/'.$user->profile_photo);
                }

                $path = $data['profile_photo']->store('profile_photos', 'public');
                $data['profile_photo'] = basename($path);
            } else {
                unset($data['profile_photo']); // إزالة إذا غير موجودة
            }

            return $this->userRepository->update($user, $data);
        });
    }
}
