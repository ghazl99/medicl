<?php

namespace Modules\Category\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepositoryInterface;

class CategoryService
{
    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories.
     *
     * @return Collection<int, Medicine>
     */
    public function getAllcategories()
    {
        return $this->categoryRepository->index();
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            $category = $this->categoryRepository->store($data);

            DB::commit();

            return $category;
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCategory(Category $category, array $data, ?UploadedFile $imageFile = null): bool
    {

        if (! $category) {
            return false;
        }

        $this->categoryRepository->update($category, $data);

        if ($imageFile) {
            $category->clearMediaCollection('category_images');

            $category->addMedia($imageFile)
                ->toMediaCollection('category_images', 'private_media');
        }

        return true;
    }
}
