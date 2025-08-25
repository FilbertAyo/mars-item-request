<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
     use HasFactory;
    protected $fillable = [
        'name',
        'region',
        'location_url',
        'status'
    ];

    public function departments()
{
    return $this->hasMany(Department::class);
}


 public function items()
{
    return $this->hasMany(Item::class);
}

}

