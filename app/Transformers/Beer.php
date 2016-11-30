<?php
namespace App\Transformers;

use League\Fractal\TransformerAbstract;

/**
 * Beer transformer
 *
 * @package App\Transformers
 */
class Beer extends TransformerAbstract
{
    /**
     * Transform brewery to something representable
     *
     * @param \App\Models\Beer $brewery
     * @return array
     */
    public function transform(\App\Models\Beer $brewery) {
        return [
            'id' => $brewery->id,
            'name' => $brewery->name,
            'volume' => $brewery->volume,
            'alcohol' => round($brewery->alcohol, 1),
            'keg' => $brewery->keg,
            'style' => $brewery->style
        ];
    }

}
