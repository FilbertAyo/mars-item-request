<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Justification extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'status'
    ];

 public function items()
{
    return $this->hasMany(Item::class);
}

}
