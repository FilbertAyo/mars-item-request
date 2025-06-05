<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TransMode extends Model
{
    protected $fillable = ['name', 'status'];

    public function trips()
    {
        return $this->hasMany(Trip::class, 'from_place');
    }
    public function petties()
    {
        return $this->hasMany(Petty::class, 'trans_mode_id');
    }
}
