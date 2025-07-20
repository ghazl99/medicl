<?php

namespace Modules\Medicine\Repositories;

use Modules\Medicine\Models\Medicine;

interface MedicineRepositoryInterface
{
    /**
     * Get all medicines.
     *
     * @return Collection<int, Medicine>
     */
    public function index();

    public function getMedicinesBySupplier($user);

    /**
     * Find a medicine by its ID.
     */
    public function findById(int $id): ?Medicine;

    /**
     * Create a new medicine.
     */
    public function store(array $data): Medicine;

    public function syncMedicinesToSupplier(array $medicineIds, int $supplierId): void;

    /**
     * Update an existing medicine.
     */
    public function update(Medicine $medicine, array $data): Medicine;

    /**
     * Delete a medicine by its ID.
     */
    public function delete(int $id): ?bool;
}
