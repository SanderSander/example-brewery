<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Brewery
 *
 * @package App\Models
 * @property integer $id
 * @property string $name
 * @property string $address
 * @property string $postalCode
 * @property string $city
 * @property double $longitude
 * @property double $latitude
 */
class Brewery extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'address', 'postalCode', 'city', 'longitude', 'latitude'
    ];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'longitude' => 'double',
        'latitude' => 'double',
    ];

    /**
     * All beers that that are made in the brewery
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function beers() {
        return $this->belongsToMany(Beer::class);
    }
}
