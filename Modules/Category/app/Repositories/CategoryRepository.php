<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;
use Modules\Category\Repositories\CategoryRepositoryInterface;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection<int, Category>
     */
    public function index()
    {
        return Category::paginate(5);
    }

    public function store(array $data)
    {
        $category = Category::create([
            'name' => $data['name'],
        ]);

        if (isset($data['image'])) {
            $category
                ->addMedia($data['image'])
                ->toMediaCollection('category_images', 'private_media'); // استخدم قرص خاص
        }

        return $category;
    }

    public function find(int $id): mixed
    {
        return Category::find($id);
    }

    public function update(int $id, array $data): mixed
    {
        $category = $this->find($id);
        if (!$category) {
            return false;
        }

        return $category->update($data);
    }
}
