<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Trip extends Model
{
    protected $fillable = ['from_place', 'petty_id'];

    public function stops()
    {
        return $this->hasMany(Stop::class);
    }
    public function startPoint()
    {
        return $this->belongsTo(StartPoint::class, 'from_place');
    }

    public function petty()
    {
        return $this->belongsTo(Petty::class, 'petty_id');
    }
}
