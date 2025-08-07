<?php

namespace Modules\Medicine\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Modules\Medicine\Models\Medicine;
use Modules\User\Models\User;

class MedicineRepository implements MedicineRepositoryInterface
{
    /**
     * Get all medicines with optional keyword search.
     */
    public function index(?string $keyword = null)
    {
        if ($keyword) {
            return Medicine::search($keyword)
                ->query(function ($query) use ($keyword) {
                    $query->with(['category', 'suppliers']);
                    $query->orWhereHas('category', function ($q) use ($keyword) {
                        $q->where('name', 'like', '%' . $keyword . '%');
                    });
                })
                ->paginate(10);
        }

        return Medicine::with(['suppliers', 'category'])->paginate(10);
    }

    /**
     * Get medicines for a supplier with optional search.
     */
    public function getMedicinesBySupplier(?string $keyword, $user)
    {
        if (! $user->hasRole('مورد')) {
            return collect(); // return empty collection for non-suppliers
        }

        if ($keyword) {
            // Get medicine IDs for the supplier
            $userMedicineIds = $user->medicines()->pluck('medicines.id');

            // Search results using Laravel Scout
            $searchResults = Medicine::search($keyword)->get();

            // Filter results to only include supplier's medicines
            $filtered = $searchResults->whereIn('id', $userMedicineIds);

            // Manual pagination
            $perPage = 10;
            $currentPage = LengthAwarePaginator::resolveCurrentPage();
            $currentItems = $filtered->slice(($currentPage - 1) * $perPage, $perPage)->values();

            return new LengthAwarePaginator(
                $currentItems,
                $filtered->count(),
                $perPage,
                $currentPage,
                ['path' => LengthAwarePaginator::resolveCurrentPath()]
            );
        }

        // Return all supplier medicines with relations
        return $user->medicines()->with(['category', 'suppliers'])->paginate(10);
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
        $supplier->medicines()->syncWithoutDetaching($medicineIds);
    }

    /**
     * Update a medicine.
     */
    public function update(Medicine $medicine, array $data): Medicine
    {
        $medicine->fill($data);
        $medicine->save();

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
     * Update notes field on the pivot record.
     */
    public function updateNoteOnPivot(int $id, ?string $notes): bool
    {
        $affected = DB::table('medicine_user')
            ->where('id', $id)
            ->update([
                'notes' => $notes,
                'updated_at' => now(),
            ]);

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
}
