<?php
declare(strict_types=1);

namespace App\Classes\Contracts;


use App\Classes\Dto\ProductDto;
use App\Models\Product;

interface ProductService
{
    /**
     * @param ProductDto $obOrderStoreDto
     * @return Product
     */
    public function store(ProductDto $obOrderStoreDto): Product;

    /**
     * @param ProductDto $obOrderStoreDto
     * @param Product $obProduct
     * @return Product
     */
    public function update(ProductDto $obOrderStoreDto, Product $obProduct): Product;

    /**
     * @param Product $obProduct
     * @return bool
     */
    public function destroy(Product $obProduct): bool;

}
