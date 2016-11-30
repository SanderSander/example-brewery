<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brewery;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class BreweryBeerController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Brewery $brewery)
    {
        $fractal = new Manager();
        $beers = new Collection($brewery->beers()->get(), new \App\Transformers\Beer());

        return response()->json($fractal->createData($beers)->toArray());
    }
}
