<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',  // foreign key
        'name',
        'quantity',
        'price',
        'amount',
        'reason',
        'justification',
        'status',
        'branch',
        'branch_comment',
        'gm_comment'
    ];

    public function user()
{
    return $this->belongsTo(User::class);
}

}
