<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatalogueFile extends Model
{
    protected $fillable = [
        'file_path',
        'user_id',
        'catalogue_id',
        'status'
    ];

    public function catalogue()
    {
        return $this->belongsTo(Catalogue::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
