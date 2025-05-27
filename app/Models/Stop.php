<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Stop extends Model
{
    protected $fillable = ['destination','trip_id'];

    public function trip()
    {
        return $this->belongsTo(Trip::class);
    }
}
