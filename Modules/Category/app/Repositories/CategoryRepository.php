<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    /**
     * Get all parent categories with their children.
     *
     * @return Collection<int, Category>
     */
    public function index()
    {
        // Fetch only parent categories with children, paginate results
        return Category::whereNull('parent_id')
            ->with('children')
            ->paginate(20);
    }

    public function getAllSubcategories(): mixed
    {
        return Category::whereNotNull('parent_id')->get();
    }

    public function getSubcategoryWithMedicines(int $subcategoryId)
    {
        return Category::with('medicines')->findOrFail($subcategoryId);
    }
    /**
     * Store a new category with optional subcategories.
     */
    public function store(array $data)
    {
        // Create main category
        $category = Category::create([
            'name' => $data['name'],
        ]);

        // Create subcategories if provided
        if (! empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (! empty($subcategoryName)) {
                    Category::create([
                        'name' => $subcategoryName,
                        'parent_id' => $category->id, // Link child to parent
                    ]);
                }
            }
        }

        return $category;
    }

    /**
     * Find category by ID.
     */
    public function find(int $id): mixed
    {
        // Return category or null
        return Category::find($id);
    }

    /**
     * Update category and replace subcategories.
     */
    public function update(Category $category, array $data): mixed
    {
        if (! $category) {
            return false;
        }

        // Update main category
        $category->update([
            'name' => $data['name'],
        ]);

        // Remove old subcategories
        $category->children()->delete();

        // Create new subcategories
        if (! empty($data['subcategories'])) {
            foreach ($data['subcategories'] as $subcategoryName) {
                if (! empty($subcategoryName)) {
                    $category->children()->create([
                        'name' => $subcategoryName,
                    ]);
                }
            }
        }

        return $category;
    }
}
