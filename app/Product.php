<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    
    // protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'description', 'price',
    ];

     /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'price' => 'int',
    ];
}
