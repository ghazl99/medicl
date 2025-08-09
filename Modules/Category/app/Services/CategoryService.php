<?php

namespace Modules\Category\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepositoryInterface;

class CategoryService
{
    use \Modules\Core\Traits\ImageTrait;

    protected CategoryRepositoryInterface $categoryRepository;

    public function __construct(CategoryRepositoryInterface $categoryRepository)
    {
        // Inject the category repository
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     */
    public function getAllcategories()
    {
        // Fetch all categories from repository
        return $this->categoryRepository->index();
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            // Create category
            $category = $this->categoryRepository->store($data);

            // If image is provided, upload with resize
            if (isset($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $category,
                    $data['image'],
                    'category_images', // Media collection name
                    'private_media',   // Disk name
                    false              // Don't replace old image
                );
            }

            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            // Rollback on error
            DB::rollBack();
            throw $e;
        }
    }

    public function updateCategory(Category $category, array $data): bool
    {
        DB::beginTransaction();

        // Ensure category exists
        if (! $category) {
            return false;
        }

        // Update category data
        $this->categoryRepository->update($category, $data);

        // If new image is provided, upload with resize and replace old
        if (isset($data['image'])) {
            $this->uploadOrUpdateImageWithResize(
                $category,
                $data['image'],
                'category_images', // Media collection name
                'private_media',   // Disk name
                true              // Don't replace old image
            );
        }

        DB::commit();
        return true;
    }
}
