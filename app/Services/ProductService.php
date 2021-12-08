<?php
declare(strict_types=1);

namespace App\Services;

use App\Classes\Contracts\ProductService as ProductServiceContract;
use App\Classes\Dto\ProductDto;
use App\Models\Product;


class ProductService implements ProductServiceContract
{
    /**
     * @inheritDoc
     */
    public function store(ProductDto $obOrderDto): Product
    {
        return Product::create($obOrderDto->toArray());
    }

    /**
     * @inheritDoc
     */
    public function update(ProductDto $obOrderDto, Product $obProduct): Product
    {
        $obProduct->update($obOrderDto->toArray());

        return $obProduct;
    }

    /**
     * @inheritDoc
     */
    public function destroy(Product $obProduct): bool
    {
        return $obProduct->delete();
    }
}
