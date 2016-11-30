<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Brewery transformer
 *
 * @package App\Transformers
 */
class Brewery extends TransformerAbstract
{
    /**
     * Transform brewery to something representable
     *
     * @param \App\Models\Brewery $brewery
     * @return array
     */
    public function transform(\App\Models\Brewery $brewery) {
        return [
            'id' => $brewery->id,
            'name' => $brewery->name,
            'address' => $brewery->address,
            'postalCode' => $brewery->postalCode,
            'city' => $brewery->city,
            // google conventions here
            'position' => [
                'lat' => $brewery->latitude,
                'lng' => $brewery->longitude
            ]
        ];
    }

}
