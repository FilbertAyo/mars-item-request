<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catalogue extends Model
{
      protected $fillable = [
        'name',
        'logo'
    ];
    public function files()
    {
        return $this->hasMany(CatalogueFile::class);
    }

    public function latestFile()
    {
        return $this->hasOne(CatalogueFile::class)->where('status', 'visible')->latestOfMany();
    }
}
