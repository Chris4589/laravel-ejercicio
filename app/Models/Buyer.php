<?php

namespace App\Models;

use App\Scopes\BuyerScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Buyer extends User //se extiende de User porque tiene los mismos atributos
{
    use HasFactory;

    protected static function boot() {
        parent::boot();
        static::addGlobalScope(new BuyerScope());
    }

    public function transactions() {
        return $this->hasMany(Transaction::class);
    }
}