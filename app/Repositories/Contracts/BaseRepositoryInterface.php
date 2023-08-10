<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    /**
     * Gets the query builder for the repository.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder();

    /**
     * Find item by id.
     *
     * @param int $id
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $with = []);

    /**
     * Find all items.
     *
     * @param mixed $perPage
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($perPage = null);

    /**
     * Create the item.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function create($data);

    /**
     * Update the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update($model, $data);

    /**
     * Update the model by id.
     *
     * @param int $id
     * @return boolean
     */
    public function updateById($id, $data);

    /**
     * Delete the model by id.
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id);

    /**
     * Gets the total count.
     *
     * @return int
     */
    public function count();
}
