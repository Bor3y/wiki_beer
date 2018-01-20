<?php

namespace App\Services;

use GuzzleHttp;


class BrewerydbService
{
    private static function get(string $url, array $params = [])
    {
        $client = Service::HttpClient('http://api.brewerydb.com/v2/', 5.0);
        try {
            $params['key'] = config('app.brewerydb_key');
            $res = $client->get($url, ['query' => $params]);
            return $res;
        } catch (GuzzleHttp\Exception\GuzzleException $exception) {
            throw $exception;
        }

    }

    public static function getRandomBeer()
    {
        try{
            $response = static::get('beer/random',[
                'hasLabels' => 'Y',
                'withBreweries' => 'Y'
            ]);
            return json_decode($response->getBody(),true);
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public static function getBeersOfBrewery(string $breweryID)
    {
        try{
            $response = static::get("brewery/{$breweryID}/beers",[]);
            return json_decode($response->getBody(),true);
        }catch (\Exception $exception){
            throw $exception;
        }
    }

    public static function search(string $searchText, string $searchType, int $pageNumber = 1)
    {
        try{
            $response = static::get("search",[
                "q" => $searchText,
                "type" => $searchType,
                "p" => $pageNumber
            ]);
            return json_decode($response->getBody(),true);
        }catch (\Exception $exception){
            throw $exception;
        }
    }
}
