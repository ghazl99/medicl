<?php

namespace Modules\Medicine\Repositories;

use Modules\Medicine\Models\Medicine;

class MedicineRepository implements MedicineRepositoryInterface
{
    /**
     * Get all medicines.
     *
     * @return Collection<int, Medicine>
     */
    public function index()
    {
        return Medicine::all();
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
    public function create(array $data): Medicine
    {
        return Medicine::create($data);
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
}
