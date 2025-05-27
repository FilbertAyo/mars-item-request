<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Petty extends Model
{
    use HasFactory;
    use HasFactory;

    protected $fillable = ['user_id', 'request_for', 'comment', 'amount', 'reason', 'request_type', 'attachment', 'status',];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lists()
    {
        return $this->hasMany(PettyList::class, 'petty_id');
    }
}
