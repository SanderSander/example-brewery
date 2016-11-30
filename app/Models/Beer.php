<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class Beer
 * @package App\Models
 * @property string $name
 * @property string $style
 * @property integer volume
 * @property float $alcohol
 * @property string $keg
 */
class Beer extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'style', 'volume', 'alcohol', 'keg',
    ];


    /**
     * All breweries that make this beer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function breweries() {
        return $this->belongsToMany(Brewery::class);
    }
}
