<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petty extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = ['user_id', 'name', 'comment', 'amount', 'reason', 'request_type', 'attachment', 'request_by', 'status',];

    public function pettyLists()
    {
        return $this->hasMany(PettyList::class, 'petty_id', 'id');
    }
}
