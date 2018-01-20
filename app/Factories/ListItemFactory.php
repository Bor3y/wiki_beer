<?php

namespace App\Factories;

use App\Models\BeerListItem;
use App\Models\BreweryListItem;
use App\Models\Contracts\ListItemInterface;

class ListItemFactory {

    public static function getListItem(string $type) {
        $class = 'App\\Models\\'.ucfirst($type).'ListItem';
        if(class_exists($class)) {
            return $class;
        }
        throw new \Exception("there is no Implementation for give type : ({$type})");
    }

}