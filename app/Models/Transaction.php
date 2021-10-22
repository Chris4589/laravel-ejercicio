<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Transaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'quantity',
        'buyer_id',
        'products_id',
    ];

    protected $dates = ['delete_at'];

    public function buyer() {
        return $this->belongsTo(Buyer::class);
    }

    public function product() {
        return $this->belongsTo(Products::class, 'products_id');
    }
}
