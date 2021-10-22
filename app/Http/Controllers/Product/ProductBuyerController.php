<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductBuyerController extends ApiController
{
    //
    public function index(Products $product) {

        $buyers = $product->transactions()
            ->with('buyer')
            ->get()
            ->pluck('buyer')
            ->collapse()
            ->unique('id')
            ->values();

        return $this->responses($buyers);
    }
}
