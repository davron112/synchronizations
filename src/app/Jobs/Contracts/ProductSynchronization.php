<?php

namespace Davron112\Synchronizations\Jobs\Contracts;

use App\Models\Product;
use Davron112\Synchronizations\Mappers\Product as Mapper;
use Davron112\Synchronizations\Mappers\ProductDetail as ProductDetailMapper;
use Illuminate\Support\Facades\DB;
use Davron112\Synchronizations\Models\Synchronization;

/**
 * Interface ProductSynchronization
 * @package Davron112\Synchronizations\Jobs\Contracts
 */
interface ProductSynchronization
{
    /**
     * @param Synchronization $synchronizationModel
     * @param Product $product
     * @param Mapper $mapper
     * @param ProductDetailMapper $productDetailMapper
     * @param DB $db
     * @return mixed
     */
    public function handle(
        Synchronization $synchronizationModel,
        Product $product,
        Mapper $mapper,
        ProductDetailMapper $productDetailMapper,
        DB $db
    );
}
