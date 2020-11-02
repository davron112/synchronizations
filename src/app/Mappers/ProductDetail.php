<?php

namespace Davron112\Synchronizations\Mappers;


use App\Models\Category;
use App\Models\Manufacturer;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use App\Library\Helper;
use App\Models\Product;

/**
 * Class ProductDetail
 * @package namespace App\Mappers
 */
class ProductDetail extends BaseMapper
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
        $characteristics = Arr::get($data, 'info');
        $categoryUuid = Arr::get($data, 'categoryUuid');
        $brand = '';
        foreach ($characteristics as $item) {
            if ($item['key'] == 'Brand') {
                $brand = $item['value'];
            }
            if ($item['key'] == 'Model') {
                $model = $item['value'];
            }
            if ($item['key'] == 'Срок гарантии') {
                $model = $item['value'];
            }
        }

        $manufacturer = Manufacturer::where('name_ru', 'LIKE', "%$brand%")->first();
        $category = Category::where('ext_id', $categoryUuid)->first();
        $categoryId = null;
        if ($category) {
            $categoryId = $category->id;
        }
        $manufacturer_id = null;
        if ($manufacturer) {
            $manufacturer_id = $manufacturer->id;
        }
        return [
            'ext_id' => Arr::get($data, 'uuid'),
            'currency' => 'uzs',
            'price' => Arr::get($data,'prices.0.price', 0),
            'quantity' =>  Arr::get($data,'totalQty', 0),
            'name_ru' =>  Arr::get($data,'name', '-'),
            'meta_title_ru' =>  Arr::get($data,'name'),
            'name_uz' =>  null,
            'category_id' =>  $categoryId,
            'manufacturer_id' =>  $manufacturer_id,
            'alias' =>  Helper::alias(new Product(), Arr::get($data,'name', Str::random(8))),
            'status' =>  0,
        ];
    }
}
