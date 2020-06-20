<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Main_Category extends Model
{
    protected $table='main__categories';
    protected $fillable = [
        'translation_lang', 'translation_of', 'name','slug','photo','active','created_at','updated_at',
    ];

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeSelection($query)
    {

        return $query->select('id', 'translation_lang', 'name', 'slug', 'photo', 'active','translation_of');
    }

}