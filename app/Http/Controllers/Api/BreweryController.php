<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Brewery;
use Illuminate\Http\Request;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class BreweryController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $fractal = new Manager();
        $breweries = new Collection(Brewery::all(), new \App\Transformers\Brewery());

        return response()->json($fractal->createData($breweries)->toArray());
    }
}
