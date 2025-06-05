<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PettyAttachment extends Model
{
    protected $fillable = [
        'petty_id',
        'name',
        'product_name',
        'attachment'
    ];

     public function petty()
    {
        return $this->belongsTo(Petty::class);
    }
}
