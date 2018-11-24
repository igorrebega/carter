<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [];

    public function carModel()
    {
        return $this->belongsTo(\App\Models\Model::class, 'model_id', 'id');
    }

    public function brand()
    {
        return $this->belongsTo(\App\Models\Brand::class);
    }

    public function color()
    {
        return $this->belongsTo(\App\Models\Color::class);
    }

    public function fuel()
    {
        return $this->belongsTo(\App\Models\Fuel::class);
    }

    public function body()
    {
        return $this->belongsTo(\App\Models\Body::class);
    }

    public function kind()
    {
        return $this->belongsTo(\App\Models\Kind::class);
    }

}
