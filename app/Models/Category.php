<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
    ];
    protected $dates = ['delete_at'];

    protected $hidden = ['pivot'];

    //nombre de la relación
    public function products() {
        return $this->belongsToMany(Products::class);
    }
}
