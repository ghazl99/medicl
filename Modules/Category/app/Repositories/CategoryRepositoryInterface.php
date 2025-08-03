<?php

namespace Modules\Category\Repositories;

use Modules\Category\Models\Category;

interface CategoryRepositoryInterface
{
    /**
     * Get all categories.
     *
     * @return Collection<int, Medicine>
     */
    public function index();

    public function store(array $data);

    public function find(int $id): mixed;

    public function update(Category $category, array $data): mixed;
}
