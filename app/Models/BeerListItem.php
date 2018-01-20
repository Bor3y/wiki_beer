<?php

namespace App\Models;

use App\Models\Contracts\ListItemInterface;
use Illuminate\Support\Facades\Validator;

class BeerListItem implements ListItemInterface
{
    private static $validationRules = [
        'name' => 'required',
        'description' => 'required',
        'labels.icon' => 'required',
    ];

    public static function getValidItems(array $data) : array
    {
        return array_reduce($data, function ($carry, $item) {
            $validator = Validator::make($item, self::$validationRules);
            if (!$validator->fails()) {
                array_push($carry, [
                    'name' => $item['name'],
                    'description' => get_words($item['description'],15),
                    'label' => $item['labels']['icon']
                ]);
            }
            return $carry;
        }, []);
    }
}