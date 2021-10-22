<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Products $product)
    {
        //
        $transactions = $product->transactions;
        return $this->responses($transactions);
    }

}
