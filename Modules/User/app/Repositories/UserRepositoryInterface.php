<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

interface UserRepositoryInterface
{
    public function getPharmacists();

    public function getSuppliers();

    public function create(array $data): User;

    public function findById(int $id): ?User;

    public function update(User $user, array $data): User;
}
