<?php

namespace Modules\Medicine\Repositories;

use Modules\Medicine\Models\Medicine;

interface MedicineRepositoryInterface
{
    /**
     * Retrieve all medicines, optionally filtered by a keyword.
     */
    public function index(?string $keyword = null);

    /**
     * Get medicines related to a specific supplier with optional keyword filter.
     */
    public function getMedicinesBySupplier(?string $keyword, $user);

    /**
     * Find a single medicine by its ID.
     */
    public function findById(int $id): ?Medicine;

    /**
     * Store a new medicine in the database.
     */
    public function store(array $data): Medicine;

    /**
     * Link medicines to a specific supplier.
     */
    public function syncMedicinesToSupplier(array $medicineIds, int $supplierId): void;

    /**
     * Update an existing medicine's data.
     */
    public function update(Medicine $medicine, array $data): Medicine;

    /**
     * Delete a medicine by its ID.
     */
    public function delete(int $id): ?bool;

    /**
     * Get pivot record between a medicine and a supplier.
     */
    public function findPivotByMedicineAndSupplier(int $medicineId, int $supplierId);

    /**
     * Update availability status for a medicine-supplier relation.
     */
    public function updatePivotAvailability(int $medicineId, int $supplierId, bool $status);

    /**
     * Update notes in the pivot table by pivot ID.
     */
    public function updateNoteOnPivot(int $id, ?string $notes): bool;

    /**
     * Update the 'is_new' status and its start/end dates for a medicine.
     */
    public function updateNewStatus(Medicine $medicine, bool $isNew, string $startDate, string $endDate): Medicine;

    public function getNewMedicines();

    // Update offer for the specific medicine-user pivot record
    public function updateOffer(int $id, ?string $offer): bool;

    /**
     * get medicine with approved supplier and category
     */
    public function findWithAvailableSuppliers(int $id): ?Medicine;
}
