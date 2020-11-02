<?php

namespace Davron112\Synchronizations\Mappers;


use Illuminate\Support\Arr;

/**
 * Class Product
 * @package namespace App\Mappers
 */
class Product extends BaseMapper
{
    /**
     * Map data.
     *
     * @param array $data data from backbone
     *
     * @return array
     */
    public function map(array $data)
    {
        $price = Arr::get($data,'prices.0.price', 0);

        return [
            'ext_id' => Arr::get($data, 'uuid'),
            'price' => $price,
            'quantity' =>  Arr::get($data,'totalQty'),
        ];
    }
}
