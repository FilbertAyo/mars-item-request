<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ApprovalLog extends Model
{
       protected $fillable = ['petty_id','item_id', 'user_id', 'action', 'comment'];

    public function petty()
    {
        return $this->belongsTo(Petty::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
