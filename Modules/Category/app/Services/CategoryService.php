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
        // Inject repository dependency
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     */
    public function getAllcategories()
    {
        // Retrieve categories from repository
        return $this->categoryRepository->index();
    }

    // Get all subcategories
    public function getAllSubcategories(): mixed
    {
        return $this->categoryRepository->getAllSubcategories();
    }

    // Get subcategory with related medicines
    public function getSubcategoryWithMedicines(int $subcategoryId)
    {
        return $this->categoryRepository->getSubcategoryWithMedicines($subcategoryId);
    }

    // Store new category with optional image
    public function store(array $data)
    {
        DB::beginTransaction();

        try {
            // Create category record
            $category = $this->categoryRepository->store($data);

            // Upload image if provided
            if (isset($data['image'])) {
                $this->uploadOrUpdateImageWithResize(
                    $category,
                    $data['image'],
                    'category_images', // Media collection
                    'private_media',   // Disk
                    false              // Don't replace existing image
                );
            }

            DB::commit();
            return $category;
        } catch (\Throwable $e) {
            // Rollback on failure
            DB::rollBack();
            throw $e;
        }
    }

    // Update existing category and optionally update image
    public function updateCategory(Category $category, array $data): bool
    {
        DB::beginTransaction();

        if (! $category) {
            return false;
        }

        // Update category data
        $this->categoryRepository->update($category, $data);

        // Upload new image and replace old one if provided
        if (isset($data['image'])) {
            $this->uploadOrUpdateImageWithResize(
                $category,
                $data['image'],
                'category_images', // Media collection
                'private_media',   // Disk
                true               // Replace old image
            );
        }

        DB::commit();
        return true;
    }
}
