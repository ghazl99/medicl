<?php

namespace Modules\Medicine\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Core\Traits\ImageTrait;
use Modules\Core\Traits\Translatable;
use Modules\Medicine\Models\Medicine;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Medicine\Repositories\MedicineRepositoryInterface;

class MedicineService
{
    use ImageTrait, Translatable;

    protected MedicineRepositoryInterface $medicineRepository;

    public function __construct(MedicineRepositoryInterface $medicineRepository)
    {
        $this->medicineRepository = $medicineRepository;
    }

    /**
     * Get all medicines (optionally filtered by keyword).
     */
    public function getAllMedicines(?string $keyword = null): LengthAwarePaginator
    {
        return $this->medicineRepository->index($keyword);
    }

    /**
     * Get medicines for a specific supplier (optionally filtered).
     */
    public function getAllMedicinesSupplier(?string $keyword, $user): LengthAwarePaginator
    {
        return $this->medicineRepository->getMedicinesBySupplier($keyword, $user);
    }

    /**
     * Find a medicine by ID.
     */
    public function getMedicineById(int $id): ?Medicine
    {
        return $this->medicineRepository->findById($id);
    }

    private function prepareUserData(array $data): array
    {
        $locale = app()->getLocale();

        $translatedName = [
            $locale => $data['type'],
        ];

        foreach ($this->otherLangs() as $lang) {
            try {
                $translatedName[$lang] = $this->autoGoogleTranslator($lang, $data['type']);
            } catch (\Exception $e) {
                Log::error("فشل ترجمة الاسم إلى [$lang]: " . $e->getMessage());
                $translatedName[$lang] = $data['type'];
            }
        }
        // dd($translatedName);
        return array_merge($data, [
            'type' => $translatedName,
        ]);
    }
    /**
     * Create a new medicine and assign it to supplier if applicable.
     */
    public function createMedicine(array $data, $user): Medicine
    {
        DB::beginTransaction();
        $image = $data['image'] ?? null;
        unset($data['image']);
        // $preparedData = $this->prepareUserData($data);
        $medicine = $this->medicineRepository->store($data);
        // dd(GoogleTranslate::trans('artrofen 320', 'ar'));
        if (! $user) {
            throw new \Exception('المستخدم غير موجود أو غير مسجل الدخول.');
        }

        if ($user->hasRole('مورد')) {
            $user->medicines()->attach($medicine->id, [
                'price' => $medicine->net_dollar_new
            ]);
        }

        if ($image) {
            // Upload with resize if size > 2048KB
            $this->uploadOrUpdateImageWithResize(
                $medicine,
                $image,
                'medicine_images',
                'private_media',
                false
            );
        }
        DB::commit();

        return $medicine;
    }

    /**
     * Assign a list of medicines to a supplier.
     */
    public function assignMedicinesToSupplier(array $medicineIds, int $supplierId): void
    {
        $this->medicineRepository->syncMedicinesToSupplier($medicineIds, $supplierId);
    }

    /**
     * Update an existing medicine.
     */
    public function updateMedicine(Medicine $medicine,  $data): Medicine
    {
        return $this->medicineRepository->update($medicine, $data);
    }

    /**
     * Delete a medicine by ID.
     */
    public function deleteMedicine(int $id): ?bool
    {
        return $this->medicineRepository->delete($id);
    }

    /**
     * Toggle availability of a medicine for a specific supplier.
     */
    public function toggleAvailability(int $medicineId, int $supplierId): bool
    {
        $pivot = $this->medicineRepository->findPivotByMedicineAndSupplier($medicineId, $supplierId);

        if (! $pivot) {
            throw new \Exception('Medicine is not linked with the supplier.');
        }

        $currentStatus = (bool) $pivot->pivot->is_available;
        $this->medicineRepository->updatePivotAvailability($medicineId, $supplierId, ! $currentStatus);

        return ! $currentStatus;
    }

    /**
     * Update  the pivot table between medicine and supplier.
     */
    public function updatePivotData(int $pivotId, array $data): bool
    {
        return $this->medicineRepository->updatePivotData($pivotId, $data);
    }

    /**
     * Update the new status and the start/end dates of a medicine.
     */
    public function updateNewStatus(Medicine $medicine, bool $isNew, string $startDate, string $endDate): Medicine
    {
        return $this->medicineRepository->updateNewStatus($medicine, $isNew, $startDate, $endDate);
    }

    public function getNewMedicines()
    {
        return $this->medicineRepository->getNewMedicines();
    }

    // Call repository to update offer
    public function updateOffer(int $id, ?string $offer): bool
    {
        return $this->medicineRepository->updateOffer($id, $offer);
    }

    public function getMedicineWithAvailableSuppliers(int $id)
    {
        return $this->medicineRepository->findWithAvailableSuppliers($id);
    }
}
