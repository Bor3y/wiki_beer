<?php

namespace App\Services;

use GuzzleHttp;


class Service
{
    public static function HttpClient(string $baseUrl,float $timeout)
    {
        return new GuzzleHttp\Client([
            'base_uri' => $baseUrl,
            'timeout'  => $timeout,
        ]);
    }
}