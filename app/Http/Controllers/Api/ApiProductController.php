<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Resources\Products\Product as ProductResource;
use App\Http\Resources\Products\ProductCollection;
use App\Repositories\Products\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ApiProductController extends ApiBaseController
{
    /**
     * @var ProductRepository
     */
    protected $productRepo;

    /**
     * @param ProductRepository $productRepo
     */
    public function __construct(ProductRepository $productRepo)
    {
        $this->productRepo = $productRepo;
    }

    /**
     * Get all products
     *
     * @return JsonResponse
     */
    public function index()
    {
        $products = $this->productRepo->getAllProduct(2);
        $productCollection= new ProductCollection($products);
        $dataResponse = $productCollection->response()->getData(true);
        return $this->apiResponseSuccess(null, 200, $dataResponse);
    }


    /**
     * @param $productId
     * @return JsonResponse
     */
    public function getDetailProduct($productId): JsonResponse
    {
        $product = $this->productRepo->getDetailProduct($productId);

        if (empty($product)) {
            return $this->apiResponseNotFound('Product dose not exist!');
        }

        $resultResource = new ProductResource($product);
        return $this->apiResponseSuccess(null, 200, $resultResource);
    }

    /**
     * Create product with data
     *
     * @param StoreProductRequest $request
     * @return JsonResponse
     */
    public function createProduct(StoreProductRequest $request): JsonResponse
    {
        $data = $request->all();

        DB::beginTransaction();
        try {
            $product = $this->productRepo->createProduct($data);
            if (!$product) {
                return $this->apiResponseError();
            }
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        return $this->apiResponseSuccess(null, 200, $product);
    }

    /**
     * Update Product by productId with data
     *
     * @param UpdateProductRequest $request
     * @param $productId
     * @return JsonResponse
     * @throws \Exception
     */
    public function updateProduct(Request $request, $productId): JsonResponse
    {
        $data = $request->all();
        $updateData = [];
        if (array_key_exists('name', $data)) {
            $updateData['name'] = $data['name'];
        }

        if (array_key_exists('price', $data)) {
            $updateData['price'] = $data['price'];
        }

        DB::beginTransaction();
        try {
            $productInfo = $this->productRepo->getDetailProduct($productId);
            if (empty($productInfo)) {
                return $this->apiResponseNotFound();
            }

            $product = $this->productRepo->updateProduct($productId, $updateData);
            if (!$product) {
                return $this->apiResponseError();
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        $resultResource = new ProductResource($product);
        return $this->apiResponseSuccess(null, 200, $resultResource);
    }

    /**
     * delete product by productId (update "is_deleted" field => '1')
     *
     * @param $productId
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteProduct($productId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $isExistProduct = $this->productRepo->isExistProduct($productId);
            if (!$isExistProduct) {
                return $this->apiResponseNotFound('Product not found');
            }
            $product = $this->productRepo->destroyProduct($productId);
            if (!$productId) {
                return $this->apiResponseError();
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }

        return $this->apiResponseSuccess(null, 200, $product);
    }
}
