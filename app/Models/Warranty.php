<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Warranty extends Model
{
    protected $fillable = [
        'customer_name',
        'model',
        'serial_number',
        'amount',
        'photo',
    ];
}
