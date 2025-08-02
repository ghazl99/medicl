<?php

namespace Modules\User\Repositories;

use Modules\User\Models\User;

interface UserRepositoryInterface
{
    public function getPharmacists(?string $keyword = null);

    public function getSuppliers(?string $keyword = null);

    public function create(array $data): User;

    public function findById(int $id): ?User;

    public function getSupplierMedicines(int $supplierId);

    public function update(User $user, array $data): User;
}
