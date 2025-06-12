<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replenishment extends Model
{

    protected $fillable = [
        'from',
        'to',
        'total_amount',
        'description',
        'status',
    ];

    /**
     * Get the petty cash records associated with the replenishment.
     */
    public function petties()
    {
        return $this->hasMany(Petty::class);
    }
}
