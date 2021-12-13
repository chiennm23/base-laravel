<?php

namespace App\Repositories\Customers;

use App\Constant\ModelConstant\BaseModelConstant;
use App\Models\CustomerModel;
use App\Repositories\BaseRepository;
use Exception;
use Symfony\Component\HttpFoundation\Response;

class CustomerRepository extends BaseRepository implements CustomerRepositoryInterface
{
    /**
     * Model Registry; (registry with __construct method of BaseRepository class)
     *
     * @return string
     */
    public function getModel(): string
    {
        return CustomerModel::class;
    }

    /**
     * Get all customers
     *
     * @return mixed
     */
    public function getAllCustomers($pagination)
    {
        return $this->model->paginate($pagination);
    }

    /**
     * create customer with data
     *
     * @param $data
     * @return false|mixed|void
     */
    public function createCustomer($data)
    {
        if (!empty($data)) {
            return $this->store($data);
        }
        return false;
    }

    /**
     * get detail customer by id
     *
     * @param $customerId
     * @return array|mixed|void
     */
    public function getDetailCustomer($customerId)
    {
        $isExistCustomer = $this->isExistCustomer($customerId);
        if (!$isExistCustomer) {
            return [];
        }

        $isNotDelete = $this->isNotDeleteCustomer($customerId);
        if (!$isNotDelete) {
            return [];
        }

        return $this->find($customerId)->toArray();
    }

    /**
     * update customer by id with data
     *
     * @param $customerId
     * @param $params
     * @return array
     */
    public function updateCustomer($customerId, $params): array
    {
        // build update data
        $resultData = [
            'meta' => false,
            'message' => '',
            'data' => [],
        ];

        // check exist customer
        $isExistCustomer = $this->isExistCustomer($customerId);
        if ($isExistCustomer == false) {
            $resultData['message'] = Response::HTTP_NOT_FOUND;
            return $resultData;
        }

        // Update
        $result = $this->update($customerId, $params);
        $customer = [
                'id' => $result->id,
                'name' => $result->name,
                'phone' => $result->phone,
                'created_at' => $result->created_at,
                'updated_at' => $result->updated_at,
        ];

        return [
            'meta' => true,
            'message' => '',
            'data' => $customer,
        ];
    }

    /**
     * delete customer by customerId (update 'is_deleted' field => 1)
     *
     * @param $customerId
     * @return string|array
     */
    public function destroyCustomer($customerId)
    {
        $result = [
            'meta' => false,
            'message' => '',
            'data' => [
                'id' => $customerId,
            ],
        ];
        $isExistCustomer = $this->isExistCustomer($customerId);
        if ($isExistCustomer == false) {
            $result['message'] = Response::HTTP_NOT_FOUND;
            return $result;
        }

        $this->delete($customerId);
        $result['meta'] = true;
        return $result;
    }

    /**
     * Check is Exist Customer
     *
     * @param $customerId
     * @return bool
     */
    public function isExistCustomer($customerId): bool
    {
        return $this->isExistRecord($customerId);
    }

    private function isNotDeleteCustomer($customerId): bool
    {
        $customer = $this->model
            ->where('id', $customerId)
            ->where('deleted_at', null)
            ->get()->toArray();

        if (empty($customer)) {
            return false;
        }

        return true;
    }
}
