<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Restaurant extends Model
{
    protected $table = 'restaurant';

    protected $fillable = [
        'name', 
        'address',
        'latitude',
        'longitude',
        'monthly_sales',
        'daily_sales',
        'specialty_id',
        'category_id',
        'open_hrs',
    ];

}
