<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petty extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = ['name', 'amount', 'reason', 'request_type', 'attachment','request_by','status'];

    public function pettyLists()
    {
        return $this->hasMany(PettyList::class);
    }
}
