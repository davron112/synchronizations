<?php

namespace Davron112\Synchronizations\Jobs\Contracts;

use Davron112\Synchronizations\Mappers\ProductDetail;

/**
 * Interface ProductSynchronization
 * @package Davron112\Synchronizations\Jobs\Contracts
 */
interface ProductDetailSynchronization
{
    /**
     * @param ProductDetail $mapper
     * @return mixed
     */
    public function handle(
        ProductDetail $mapper
    );
}
