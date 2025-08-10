<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

interface CategoryRepositoryInterface
{
    public function index();

    public function getAllSubcategories(): mixed;

    public function getSubcategoryWithMedicines(int $subcategoryId);

    public function store(array $data);

    public function find(int $id): mixed;

    public function update(Category $category, array $data): mixed;
}
