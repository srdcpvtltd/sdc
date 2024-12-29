<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\City;

class PoliceStation extends Model
{
    use HasFactory;

    public function city(){
        return $this->belongsTO(City::class, 'city_id');
    }
}
