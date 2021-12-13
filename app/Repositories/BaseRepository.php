<?php

namespace App\Repositories;

use Illuminate\Contracts\Container\BindingResolutionException;
use Symfony\Component\HttpFoundation\Response;

class BaseRepository implements BaseRepositoryInterface
{
    protected $model;

    public function __construct()
    {
        $this->setModel();
    }

    /**
     * @return mixed
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @throws BindingResolutionException
     */
    public function setModel(): void
    {
        $this->model = app()->make(
            $this->getModel()
        );
    }

    /**
     * Get all data for model
     *
     * @return mixed
     */
    public function getAll()
    {
        return $this->model->all();
    }

    /**
     * Get data for model by id
     *
     * @param $id
     * @return mixed|void
     */
    public function find($id)
    {
        return $this->model->find($id);
    }

    /**
     * Create data of model with $attributes
     *
     * @param array $attributes
     * @return mixed|void
     */
    public function store($attributes = [])
    {
        return $this->model->create($attributes);
    }

    /**
     * Update data of model by $id with $attributes
     *
     * @param $id
     * @param array $attributes
     * @return mixed|void
     */
    public function update($id, $attributes = [])
    {
        $result = $this->find($id);

        return tap($result)->update($attributes);
    }

    /**
     * Delete data by $id
     *
     * @param $id
     * @return mixed|void
     */
    public function delete($id)
    {
        $result = $this->find($id);
        return $result->delete($id);
    }

    /**
     * check is exist record by id
     *
     * @param $id
     * @return bool
     */
    public function isExistRecord($id): bool
    {
        $result = $this->find($id);

        if (!$result) {
            return false;
        }

        return true;
    }
}
