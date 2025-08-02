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
        return Category::whereNull('parent_id')->with('children')->paginate(20);
    }

    public function store(array $data)
    {
        $category = Category::create([
            'name' => $data['name'],
        ]);

        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (!empty($subcategoryName)) {
                    Category::create([
                        'name' => $subcategoryName,
                        'parent_id' => $category->id, // ربط القسم الفرعي بالقسم الرئيسي
                    ]);
                }
            }
        }
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

    public function update(Category $category, array $data): mixed
    {
        if (!$category) {
            return false;
        }

        // تحديث اسم القسم
        $category->update([
            'name' => $data['name'],
        ]);

        // حذف الأقسام الفرعية القديمة
        $category->children()->delete();

        // إعادة إدخال الأقسام الفرعية الجديدة
        if (!empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (!empty($subcategoryName)) {
                    $category->children()->create([
                        'name' => $subcategoryName,
                    ]);
                }
            }
        }

        return $category;
    }
}
