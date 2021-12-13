<?php

namespace App\Repositories\Products;

use App\Models\ProductModel;
use App\Repositories\BaseRepository;

class ProductRepository extends BaseRepository implements ProductRepositoryInterface
{
    /**
     * Model Registry; (registry with __construct method of BaseRepository class)
     *
     * @return string
     */
    public function getModel(): string
    {
        return ProductModel::class;
    }

    /**
     * @return mixed
     */
    public function getAllProduct($pagination)
    {
        return $this->model->paginate($pagination);
    }


    /**
     * Get detail product by productId
     *
     * @param $productId
     * @return mixed
     */
    public function getDetailProduct($productId)
    {
        $isExistProduct = $this->isExistProduct($productId);
        if (!$isExistProduct) {
            return [];
        }
        return $this->find($productId);
    }

    /**
     * @param $data
     * @return false|mixed|void
     */
    public function createProduct($data)
    {
        if (!$this->store($data)) {
            return false;
        }

        return true;
    }


    /**
     * Update product by productId
     *
     * @param $productId
     * @param $params
     * @return false|mixed|void
     */
    public function updateProduct($productId, $params)
    {
        // Update
        $result = $this->update($productId, $params);

        if (!$result) {
            return false;
        }

        return $result;
    }

    /**
     * delete product by productId (update 'is_deleted' field => 1)
     *
     * @param $productId
     * @return false
     */
    public function destroyProduct($productId): bool
    {
        // delete
        $result = $this->delete($productId);
        if (!$result) {
            return false;
        }
        return true;
    }

    /**
     * Check exist product with productId
     *
     * @param $productId
     * @return bool
     */
    public function isExistProduct($productId): bool
    {
        return $this->isExistRecord($productId);
    }
}
