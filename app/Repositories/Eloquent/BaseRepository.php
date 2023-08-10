<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\BaseRepositoryInterface;
use Illuminate\Database\Eloquent\Model;

class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Model for the repository.
     */
    protected $model;


    /**
     * Construct the repository.
     *
     * @param Model $model
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    /**
     * Gets the query builder for the repository.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getBuilder()
    {
        return $this->model->newQuery();
    }

    /**
     * Find item by id.
     *
     * @param int $id
     * @param array $with
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find($id, $with = [])
    {
        return $this->model->with($with)->find($id);
    }

    /**
     * Find all items.
     *
     * @param mixed $perPage
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function get($perPage = null)
    {
        if ( $perPage )
            return $this->model->paginate($perPage);

        return $this->model->get();
    }

    /**
     * Create the item.
     *
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function create($data)
    {
        return $this->model->create($data);
    }

    /**
     * Update the model.
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     * @param array $data
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function update($model, $data)
    {
        $model->update($data);

        return $model;
    }

    /**
     * Update the model by id.
     *
     * @param int $id
     * @return boolean
     */
    public function updateById($id, $data)
    {
        $model = $this->find($id);

        return $this->update($model, $data);
    }

    /**
     * Delete the model by id.
     *
     * @param int $id
     * @return boolean
     */
    public function deleteById($id)
    {
        return $this->find($id)->delete();
    }

    /**
     * Gets the total count.
     *
     * @return int
     */
    public function count()
    {
        return $this->model->count();
    }

}
