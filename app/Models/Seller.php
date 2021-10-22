<?php

namespace App\Models;

use App\Scopes\SellerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Seller extends User //se extiende de User porque tiene los mismos atributos
{
    use HasFactory;

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new SellerScope);
    }

    public function products() {
        return $this->hasMany(Products::class);
    }
}

