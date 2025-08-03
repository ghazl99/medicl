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
    public function index(?string $keyword = null);

    public function getMedicinesBySupplier(?string $keyword = null, $user);

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

    /**
     * Find the pivot record between a medicine and a supplier.
     *
     * This method retrieves the pivot row from the 'medicine_user' table
     * that links the given medicine with the specified supplier.
     *
     * @param  int  $medicineId  The ID of the medicine.
     * @param  int  $supplierId  The ID of the supplier (user).
     * @return mixed Returns the pivot model instance or null if not found.
     */
    public function findPivotByMedicineAndSupplier(int $medicineId, int $supplierId);

    /**
     * Update the 'is_available' status in the pivot table for a specific medicine and supplier.
     *
     * This method updates the availability status (true/false) for the
     * medicine-supplier relationship in the 'medicine_user' pivot table.
     *
     * @param  int  $medicineId  The ID of the medicine.
     * @param  int  $supplierId  The ID of the supplier (user).
     * @param  bool  $status  The new availability status (true = available, false = not available).
     * @return bool Returns true if the update was successful, otherwise false.
     */
    public function updatePivotAvailability(int $medicineId, int $supplierId, bool $status);
}
