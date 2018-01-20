<?php

namespace App\Models\Contracts;

interface ListItemInterface{

    public static function getValidItems(array $data) : array ;

}