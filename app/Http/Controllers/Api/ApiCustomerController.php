<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Http\Resources\Customers\Customer as CustomerResource;
use App\Http\Resources\Customers\CustomerCollection;
use App\Repositories\Customers\CustomerRepository;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class ApiCustomerController extends ApiBaseController
{
    /**
     * @var CustomerRepository
     */
    protected $customerRepo;

    /**
     *
     *
     * @param CustomerRepository $customerRepo
     */
    public function __construct(CustomerRepository $customerRepo)
    {
        $this->customerRepo = $customerRepo;
    }

    /**
     * Get all customer
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $customers = $this->customerRepo->getAllCustomers(2);
        $customerCollection= new CustomerCollection($customers);
        $dataResponse = $customerCollection->response()->getData(true);
        return $this->apiResponseSuccess(null, Response::HTTP_OK, $dataResponse);
    }

    /**
     * @param StoreCustomerRequest $request
     * @return JsonResponse
     * @throws Exception
     */
    public function createCustomer(StoreCustomerRequest $request): JsonResponse
    {

        $data = $request->all();

        DB::beginTransaction();
        try {
            $customer = $this->customerRepo->createCustomer($data);
            if (!$customer) {
                return $this->apiResponseError();
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }

        return $this->apiResponseSuccess(null, Response::HTTP_OK, $customer);
    }

    /**
     * get detail customer by id
     *
     * @param string $customerId
     * @return JsonResponse
     */
    public function getDetailCustomer(string $customerId): JsonResponse
    {
        $customer = $this->customerRepo->getDetailCustomer($customerId);

        if (!$customer) {
            return $this->apiResponseNotFound('Customer not exist!', false);
        }
        $resultResource = new CustomerResource($customer);
        return $this->apiResponseSuccess(null, Response::HTTP_OK, $resultResource);
    }

    /**
     * Update Customer
     *
     * @param UpdateCustomerRequest $requestUpdateCustomer
     * @param $customerId
     * @return JsonResponse
     * @throws Exception
     */
    public function updateCustomer(UpdateCustomerRequest $requestUpdateCustomer, $customerId): JsonResponse
    {
        $data = $requestUpdateCustomer->all();
        $customerParams = [];

        if (array_key_exists('name', $data)) {
            $customerParams['name'] = $data['name'];
        }

        if (array_key_exists('phone', $data)) {
            $customerParams['phone'] = $data['phone'];
        }

        DB::beginTransaction();
        try {
            $customer = $this->customerRepo->updateCustomer($customerId, $customerParams);
            if ($customer['meta'] == false) {
                if ($customer['message'] == Response::HTTP_NOT_FOUND) {
                    return $this->apiResponseNotFound('Customer dose not exist!');
                } else {
                    return $this->apiResponseError();
                }
            }

            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }

        return $this->apiResponseSuccess(null, Response::HTTP_OK, $customer);
    }

    /**
     * delete customer by customerId (update "is_deleted" field => '1')
     *
     * @param $customerId
     * @return JsonResponse
     */
    public function deleteCustomer($customerId): JsonResponse
    {
        DB:: beginTransaction();

        try {
            $customer = $this->customerRepo->destroyCustomer($customerId);
            if ($customer['meta'] == false) {
                if ($customer['message'] == Response::HTTP_NOT_FOUND) {
                    return $this->apiResponseNotFound('Customer dose not exist!');
                } else {
                    return $this->apiResponseError(['delete false!'], 500);
                }
            }
            DB::commit();
        } catch (Exception $exception) {
            DB::rollBack();
            throw new Exception($exception->getMessage());
        }

        return $this->apiResponseSuccess(null, Response::HTTP_OK, $customer);
    }
}
