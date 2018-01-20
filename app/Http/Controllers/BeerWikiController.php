<?php

namespace App\Http\Controllers;

use App\Factories\ListItemFactory;
use App\Http\Requests\SearchRequest;
use App\Models\RandomBeer;
use App\Services\BrewerydbService;
use GuzzleHttp;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\ValidationException;

class BeerWikiController extends Controller
{
    public function getRandomBeer()
    {
        try {
            for ($i = 0; $i < 5; $i++) {
                $randomBeer = BrewerydbService::getRandomBeer();
                try {
                    return response()->json((array)RandomBeer::getRandomBeer($randomBeer['data']), 200);
                } catch (ValidationException $exception) {
                    if ($i == 4) {
                        report(new \Exception('5 tries with out valid response'));
                        return response()->json([
                            'message' => 'something wrong done',
                        ], 500);
                    }
                }

            }
        } catch (\Exception $e) {
            return self::ReportUnExpectedException($e);
        }
    }

    public function getBeersOfBrewery($breweryId)
    {
        // check cache first
        $cachedBeers = Cache::get("brewery_{$breweryId}_beers");
        if($cachedBeers != null)
            return $cachedBeers;

        try {
            $beers = BrewerydbService::getBeersOfBrewery($breweryId);
            $beerListItemClass = ListItemFactory::getListItem('beer');
            $ValidBeers = $beerListItemClass::getValidItems($beers['data']);

            // put the value in the cache for 1 day
            Cache::put("brewery_{$breweryId}_beers", $ValidBeers, 24*60);
            return $ValidBeers;
        } catch (GuzzleHttp\Exception\ClientException $e) {
            if ($e->getCode() == 404) {
                return response()->json([
                    'message' => 'wrong brewery id',
                ], 422);
            }
            return self::ReportUnExpectedException($e);
        } catch (\Exception $e) {
            return self::ReportUnExpectedException($e);
        }
    }


    public function search(SearchRequest $request){
        //$page = $request->page ??  1;
        $page = 1;

        // check cache
        $cachedBeers = Cache::get("search_type_{$request->type}_query_{$request->q}_page_{$page}");
        if($cachedBeers != null)
            return $cachedBeers;
        try {
            $results = BrewerydbService::search($request->q,$request->type,$page);
            $data = $results['data'] ?? [];
            $listItemClass = ListItemFactory::getListItem($request->type);
            $ValidResults = $listItemClass::getValidItems($data);

            // put the value in the cache for 1 day
            Cache::put("search_type_{$request->type}_query_{$request->q}_page_{$page}", $ValidResults, 24*60);
            return $ValidResults;
        } catch (\Exception $e) {
            return self::ReportUnExpectedException($e);
        }
    }
}
