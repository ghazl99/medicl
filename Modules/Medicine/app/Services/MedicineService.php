<?php

namespace Modules\Medicine\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Medicine\Repositories\MedicineRepositoryInterface;

class MedicineService
{
    protected MedicineRepositoryInterface $medicineRepository;

    public function __construct(MedicineRepositoryInterface $medicineRepository)
    {
        $this->medicineRepository = $medicineRepository;
    }

    /**
     * Get all medicines.
     *
     * @return Collection<int, Medicine>
     */
    public function getAllMedicines()
    {
        return $this->medicineRepository->index();
    }

    public function getAllMedicinesSupplier($user)
    {
        return $this->medicineRepository->getMedicinesBySupplier($user);
    }
    /**
     * Get medicines filtered by 'name' or 'manufacturer' using a single search term.
     *
     * @param  array  $filters  Array containing 'search_term'
     * @return \Illuminate\Database\Eloquent\Collection
     */

    /**
     * Find a medicine by its ID.
     */
    public function getMedicineById(int $id): ?Medicine
    {
        return $this->medicineRepository->findById($id);
    }

    /**
     * Create a new medicine.
     */
    public function createMedicine(array $data, $user): Medicine
    {
        return DB::transaction(function () use ($data, $user) {
            $medicine = $this->medicineRepository->store($data);

            if (! $user) {
                throw new \Exception('المستخدم غير موجود أو غير مسجل الدخول.');
            }

            if ($user->hasRole('مورد')) {
                $user->Medicines()->attach($medicine->id);
            }

            return $medicine;
        });
    }

    public function assignMedicinesToSupplier(array $medicineIds, int $supplierId): void
    {
        $this->medicineRepository->syncMedicinesToSupplier($medicineIds, $supplierId);
    }

    /**
     * Update an existing medicine.
     */
    public function updateMedicine(Medicine $medicine, array $data): Medicine
    {
        return $this->medicineRepository->update($medicine, $data);
    }

    /**
     * Delete a medicine by its ID.
     */
    public function deleteMedicine(int $id): ?bool
    {
        return $this->medicineRepository->delete($id);
    }

    /**
     * Toggle availability for a medicine-supplier relationship.
     */
    public function toggleAvailability(int $medicineId, int $supplierId): bool
    {
        // Find the pivot record
        $pivot = $this->medicineRepository->findPivotByMedicineAndSupplier($medicineId, $supplierId);

        if (! $pivot) {
            throw new \Exception('Medicine is not linked with the supplier.');
        }

        // Flip availability
        $currentStatus = (bool) $pivot->pivot->is_available;
        $this->medicineRepository->updatePivotAvailability($medicineId, $supplierId, ! $currentStatus);

        return ! $currentStatus;
    }
}
