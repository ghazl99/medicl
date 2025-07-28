<?php

namespace Modules\Category\Services;
use Illuminate\Http\UploadedFile;

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
        return $this->categoryRepository->store($data);
    }

    public function updateCategory(int $id, array $data, ?UploadedFile $imageFile = null): bool
    {
        $category = $this->categoryRepository->find($id);
        if (!$category) {
            return false;
        }

        $category->update($data);

        if ($imageFile) {
            $category->clearMediaCollection('category_images');

            $category->addMedia($imageFile)
                ->toMediaCollection('category_images', 'private_media');
        }

        return true;
    }
}
