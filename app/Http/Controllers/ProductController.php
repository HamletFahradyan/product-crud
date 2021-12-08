<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Classes\Exceptions\ProductNotFoundException;
use App\Http\Requests\ProductRequest;
use App\Http\Resources\ProductResource;
use App\Models\Product;
use App\Services\ProductService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    /**
     * @var ProductService $obProductService
     */
    private ProductService $obProductService;

    /**
     * OrderController constructor.
     * @param ProductService $obProductService
     */
    public function __construct(ProductService $obProductService)
    {
        $this->obProductService = $obProductService;
    }

    /**
     * @param ProductRequest $obProductRequest
     * @return ProductResource
     */
    public function store(ProductRequest $obProductRequest): ProductResource
    {
        $obProductDto = $obProductRequest->getDto();
        $obProduct = $this->obProductService->store($obProductDto);

        return ProductResource::make($obProduct)
            ->additional([
                'status'  => 'success',
                'message' => 'Product created successfully',
            ]);
    }

    /**
     * @param int $iId
     * @param ProductRequest $obProductRequest
     * @return ProductResource
     */
    public function update(int $iId, ProductRequest $obProductRequest): ProductResource
    {
        try {
            $userId = Auth::id();
            $obProduct = Product::whereUserId($userId)->findOrFail($iId);
            $obProductDto = $obProductRequest->getDto();
            $obProduct = $this->obProductService->update($obProductDto, $obProduct);

            return ProductResource::make($obProduct)
                ->additional([
                    'status' => 'success',
                    'message' => 'Product updated successfully',
                ]);
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException();
        }
    }

    /**
     * @param int $iId
     * @return JsonResponse
     */
    public function destroy(int $iId): JsonResponse
    {
        try {
            $userId = Auth::id();
            $obProduct = Product::whereUserId($userId)->findOrFail($iId);
            $this->obProductService->destroy($obProduct);

            return response()->json(['status' => 'success']);
        } catch (ModelNotFoundException $e) {
            throw new ProductNotFoundException();
        }
    }
}
