<?php

namespace Modules\Medicine\Services;

use Illuminate\Database\Eloquent\Collection;
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

    /**
     * Get medicines filtered by 'name' or 'manufacturer' using a single search term.
     *
     * @param  array  $filters  Array containing 'search_term'
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getFilteredMedicinesByNamesOrManufacturer(array $filters = [])
    {
        $query = Medicine::query(); // ابدأ باستعلام على موديل Medicine
        $searchTerm = $filters['search_term'] ?? null;

        if ($searchTerm) {
            $query->where(function ($q) use ($searchTerm) {
                // البحث في عمود 'name' (اسم الدواء)
                $q->where('name', 'like', '%'.$searchTerm.'%');

                // أو البحث في عمود 'manufacturer' (الشركة المصنعة)
                $q->orWhere('manufacturer', 'like', '%'.$searchTerm.'%');
            });
        }

        return $query->get();
    }

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
    public function createMedicine(array $data): Medicine
    {
        return $this->medicineRepository->create($data);
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
}
