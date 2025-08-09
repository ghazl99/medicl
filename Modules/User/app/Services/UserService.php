<?php

namespace Modules\User\Services;

use Modules\User\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Modules\User\Repositories\UserRepositoryInterface;

class UserService
{
    use \Modules\Core\Traits\ImageTrait;

    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getPharmacists(?string $keyword = null)
    {
        return $this->userRepository->getPharmacists($keyword);
    }

    public function getSuppliers(?string $keyword = null)
    {
        return $this->userRepository->getSuppliers($keyword);
    }

    public function registerUser(array $data, $image = null)
    {
        return DB::transaction(function () use ($data) {
            $cities = $data['cities'] ?? [];
            unset($data['cities']);
            if (isset($data['role']) && $data['role'] === 'مورد') {
                $data['name'] = $data['workplace_name'];
            }
            $data['password'] = Hash::make($data['password']);
            if (isset($data['role'])) {
                if ($data['role'] === 'صيدلي') {
                    $data['is_approved'] = 1;
                } elseif ($data['role'] === 'مورد') {
                    $data['is_approved'] = 0;
                }
            }
            $user = $this->userRepository->create($data);

            if (!empty($cities)) {
                $user->cities()->sync($cities);
            }

            return $user;
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
        DB::beginTransaction();

        if (isset($data['profile_photo'])) {
            $this->uploadOrUpdateImageWithResize(
                    $user,
                    $data['profile_photo'],
                    'profile_photo', // Media collection name
                    'private_media',   // Disk name
                    true              // Don't replace old image
                );
            unset($data['profile_photo']);
        }

        $regularData = collect($data)->except('cities')->toArray();
        $this->userRepository->update($user, $regularData);

        if (isset($data['cities'])) {
            $user->cities()->sync($data['cities']);
        }
        DB::commit();
        return $user;
    }
}
