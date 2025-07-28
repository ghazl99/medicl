<?php

namespace Modules\Category\Repositories;

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
    public function update(int $id, array $data): mixed;
}
