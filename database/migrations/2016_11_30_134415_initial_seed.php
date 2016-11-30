<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class InitialSeed extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $breweries = collect(json_decode(File::get(base_path('database/migrations/stubs/breweries.json')), true)['breweries']);
        $breweryBeers = collect(json_decode(File::get(base_path('database/migrations/stubs/beers.json')), true)['beers']);

        // Transform and insert data
        $breweries->each(function($brewery) use ($breweryBeers) {
            // naming convention fixes
            $brewery['postalCode'] = $brewery['zipcode'];
            unset($brewery['open']);
            unset($brewery['zipcode']);

            // filter beers for brewery
            $beers = $breweryBeers->filter(function($beer) use ($brewery) {
                return $beer['brewery'] === $brewery['name'];
            });
            $beers = $beers->map(function($beer) {
                unset($beer['brewery']);
                return $beer;
            });

            // Lookup geo data
            $enrich = json_decode(file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . urlencode($brewery['address'] . ', ' . $brewery['city'])));
            $location = $enrich->results[0]->geometry->location;
            $brewery['longitude'] = $location->lng;
            $brewery['latitude'] = $location->lat;

            // Create brewery and the beers
            $brewery['id'] = DB::table('breweries')->insertGetId($brewery);
            $beers->each(function($beer) use ($brewery) {
                $id = DB::table('beers')->insertGetId($beer);

                DB::table('beer_brewery')->insert([
                    'beer_id' => $id,
                    'brewery_id' => $brewery['id']
                ]);
            });
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // truncate the table so nothing is left
        DB::table('breweries')->truncate();
    }
}
