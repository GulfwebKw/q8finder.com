<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class VenueType extends Model
{
    use SoftDeletes;
    protected $guarded=['id'];
    public $table="venue_type";

    public function advertising(){
        return  $this->hasMany(Advertising::class, 'venue_type');
    }

}
