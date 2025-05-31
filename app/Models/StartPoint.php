<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StartPoint extends Model
{
    protected $fillable = ['name','status'];

    public function trips()
{
    return $this->hasMany(Trip::class, 'from_place');
}
}
