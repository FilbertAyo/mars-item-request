<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Website extends Model
{
    protected $fillable = ['hero_title','hero_text', 'hero_image', 'intro_image','about_image'];
}
