<?php

use Illuminate\Database\Seeder;

class BreweriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(App\Models\Brewery::class, 300)->create()->each(function(\App\Models\Brewery $brewery) {

            // Attach a few beers to the brewery
            $beers = \App\Models\Beer::inRandomOrder()->limit(rand(2, 5))->get();
            $beers = $beers->map(function($beer) {
                return $beer->id;
            })->toArray();
            $brewery->beers()->sync($beers);
        });
    }
}
