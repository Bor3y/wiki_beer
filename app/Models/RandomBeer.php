<?php

namespace App\Models;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class RandomBeer
{
    public $name;
    public $description;
    public $label;
    public $breweriesIds;

    private static $validationRules = [
        'name' => 'required',
        'description' => 'required',
        'labels.medium' => 'required',
        'breweries' => 'required|array',
        'breweries.*.id' => 'required',
    ];

    public function __construct($name, $description, $label,$breweriesIds)
    {
        $this->name = $name;
        $this->description = $description;
        $this->label = $label;
        $this->breweriesIds = $breweriesIds;
    }

    public static function getRandomBeer($data)
    {
        $validator = Validator::make($data, self::$validationRules);

        if (!$validator->fails()) {
            return new RandomBeer($data['name'], $data['description'], $data['labels']['medium'], array_map(function ($item) {
                return $item['id'];
            }, $data['breweries']));
        }

        throw ValidationException::withMessages($validator->errors()->all());
    }
}