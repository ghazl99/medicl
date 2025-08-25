<?php

namespace Modules\Medicine\Repositories;

use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\Medicine\Models\MedicineUser;
use Modules\User\Models\User;

class MedicineRepository implements MedicineRepositoryInterface
{
    /**
     * Get all medicines with optional keyword search.
     */
    public function index(?string $keyword = null)
    {
        $query = Medicine::with(['suppliers', 'category']);

        if ($keyword) {
            $query = Medicine::search($keyword)
                ->query(function ($query) use ($keyword) {
                    $query->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    });
                });
        }

        $medicines = $query->paginate(10)->appends(['search' => $keyword]);

        // This runs on every site visit and updates medicines to not new if their new_end_date has passed.
        foreach ($medicines as $medicine) {
            if ($medicine->is_new && $medicine->new_end_date < now()) {
                $medicine->update(['is_new' => false]);
            }
        }

        return $medicines;
    }

    /**
     * Get medicines for a supplier with optional search.
     */
    public function getMedicinesBySupplier(?string $keyword, $user)
    {
        if ($user->hasRole('مورد')) {
            // Start with the user's medicines query
            $query = $user->medicines()->with(['category', 'suppliers']);

            if ($keyword) {
                // Use Scout to get the matching medicines
                $searchResults = Medicine::search($keyword)->get();

                // Pluck the IDs from the resulting collection
                $medicineIds = $searchResults->pluck('id');

                // Now, filter the user's medicines query to only include those IDs
                $query->whereIn('medicines.id', $medicineIds);
            }

            // Paginate the final, filtered query
            return $query->paginate(10);
        }

        return collect();
    }
    /**
     * Find a medicine by ID.
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

    /**
     * Assign medicines to a supplier (without removing existing).
     */
    public function syncMedicinesToSupplier(array $medicineIds, int $supplierId): void
    {
        $supplier = User::findOrFail($supplierId);
        $pivotData = [];
        foreach ($medicineIds as $medicineId) {
            $medicine = Medicine::findOrFail($medicineId);
            $pivotData[$medicineId] = ['price' => $medicine->net_syp];
        }
        $supplier->medicines()->syncWithoutDetaching($pivotData);
    }

    /**
     * Update a medicine.
     */
    public function update(Medicine $medicine,  $data): Medicine
    {
        $medicine->update($data);
        return $medicine;
    }

    /**
     * Delete a medicine by ID.
     */
    public function delete(int $id): ?bool
    {
        $medicine = $this->findById($id);

        return $medicine ? $medicine->delete() : null;
    }

    /**
     * Get the pivot record for a specific medicine and supplier.
     */
    public function findPivotByMedicineAndSupplier(int $medicineId, int $supplierId)
    {
        $supplier = User::findOrFail($supplierId);

        return $supplier->medicines()->where('medicine_id', $medicineId)->first();
    }

    /**
     * Update the 'is_available' flag on the pivot table.
     */
    public function updatePivotAvailability(int $medicineId, int $supplierId, bool $status)
    {
        $supplier = User::findOrFail($supplierId);

        return $supplier->medicines()->updateExistingPivot($medicineId, [
            'is_available' => $status,
        ]);
    }

    /**
     * Update notes or price field on the pivot record.
     */
    public function updatePivotData(int $id, array $data): bool
    {
        $data['updated_at'] = now();

        $affected = DB::table('medicine_user')
            ->where('id', $id)
            ->update($data);

        return $affected > 0;
    }


    /**
     * Update the 'is_new' flag and new_start_date/new_end_date columns.
     */
    public function updateNewStatus(Medicine $medicine, bool $isNew, string $startDate, string $endDate): Medicine
    {
        $medicine->is_new = $isNew;
        $medicine->new_start_date = $startDate;
        $medicine->new_end_date = $endDate;
        $medicine->save();

        return $medicine;
    }

    // get all new medicines
    public function getNewMedicines()
    {
        // Fetch medicines where is_new is true and dates are valid
        return Medicine::where('is_new', true)
            ->whereDate('new_start_date', '<=', now())
            ->whereDate('new_end_date', '>=', now())
            ->with('category') // optional: load related data
            ->latest()
            ->paginate(10);
    }

    /**
     * Update offer by quantity.
     **/
    public function updateOffer(int $id, ?string $offer): bool
    {
        $updated = DB::table('medicine_user')
            ->where('id', $id) // أو استخدم الشرط المناسب حسب مفتاح الجدول
            ->update(['offer' => $offer]); // Find pivot model
        return $updated > 0; // Save updated offer
    }

    public function findWithAvailableSuppliers(int $id): ?Medicine
    {
        return Medicine::with('category')->availableSuppliers()->find($id);
    }
}
