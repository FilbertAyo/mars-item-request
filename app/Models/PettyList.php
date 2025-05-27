<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PettyList extends Model
{
    use HasFactory;
    protected $fillable = ['petty_id', 'item_name','quantity', 'price'];

    public function petty()
{
    return $this->belongsTo(Petty::class, 'petty_id');
}

}
