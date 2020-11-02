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
        $price = (int) Arr::get($data,'prices.0.price', 0);
        $qty = (int) Arr::get($data,'totalQty', 0);

        $data = [
            'ext_id' => Arr::get($data, 'uuid'),
            'price' => $price,
            'quantity' =>  $qty,
        ];
        if ($price < 2000 || $qty < 1) {
            $data = array_merge(
                [
                    'status' => 0
                ], $data
            );
        }
        return $data;

    }
}
