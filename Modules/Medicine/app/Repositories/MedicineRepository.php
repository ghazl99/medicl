<?php

namespace Modules\Medicine\Repositories;

use Modules\Medicine\Models\Medicine;
use Modules\User\Models\User;

class MedicineRepository implements MedicineRepositoryInterface
{
    /**
     * Get all medicines.
     *
     * @return Collection<int, Medicine>
     */
    public function index(?string $keyword = null)
    {
        if ($keyword) {
            return Medicine::search($keyword)
                ->query(function ($query) use ($keyword) {
                    $query->with(['category', 'suppliers']);

                    $query->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%'.$keyword.'%');
                    });
                })
                ->paginate(5);
        }

        return Medicine::with(['suppliers', 'category'])->paginate(5);
    }

    public function getMedicinesBySupplier($user)
    {
        if ($user->hasRole('مورد')) {
            return $user->Medicines()->paginate(5);
        }
    }

    /**
     * Find a medicine by its ID.
     */
    public function findById(int $id): ?Medicine
    {
        return Medicine::find($id);
    }

    /**
     * Create a new medicine.
     */
    public function store(array $data): Medicine
    {
        return Medicine::create($data);
    }

    public function syncMedicinesToSupplier(array $medicineIds, int $supplierId): void
    {
        $supplier = User::findOrFail($supplierId);
        $supplier->medicines()->syncWithoutDetaching($medicineIds); // many-to-many العلاقة
    }

    /**
     * Update an existing medicine.
     */
    public function update(Medicine $medicine, array $data): Medicine
    {
        $medicine->fill($data);
        $medicine->save();

        return $medicine;
    }

    /**
     * Delete a medicine by its ID.
     */
    public function delete(int $id): ?bool
    {
        $medicine = $this->findById($id);
        if ($medicine) {
            return $medicine->delete();
        }

        return null; // Or throw an exception if not found
    }

    /**
     * Find the pivot record linking a specific medicine and supplier.
     *
     * This method retrieves the relationship record from the 'medicine_user' pivot table
     * that connects the specified medicine with the given supplier (user).
     *
     * @param  int  $medicineId  The ID of the medicine.
     * @param  int  $supplierId  The ID of the supplier (user).
     * @return mixed Returns the pivot model instance or null if the record does not exist.
     */
    public function findPivotByMedicineAndSupplier(int $medicineId, int $supplierId)
    {
        $supplier = User::findOrFail($supplierId);

        return $supplier->medicines()->where('medicine_id', $medicineId)->first();
    }

    /**
     * Update the 'is_available' status for a specific medicine and supplier in the pivot table.
     *
     * This method updates the availability status (true = available, false = unavailable)
     * for the relationship between the given medicine and supplier.
     *
     * @param  int  $medicineId  The ID of the medicine.
     * @param  int  $supplierId  The ID of the supplier (user).
     * @param  bool  $status  The new availability status.
     * @return bool Returns true if the pivot record was updated successfully.
     */
    public function updatePivotAvailability(int $medicineId, int $supplierId, bool $status)
    {
        $supplier = User::findOrFail($supplierId);

        return $supplier->medicines()->updateExistingPivot($medicineId, [
            'is_available' => $status,
        ]);
    }
}
