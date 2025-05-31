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
        'justification_id',
        'status',
        'branch_id',
        'p_type'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function justification()
    {
        return $this->belongsTo(Justification::class);
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class);
    }
}
