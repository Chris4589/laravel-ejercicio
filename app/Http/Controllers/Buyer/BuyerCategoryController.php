<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //
        $category = $buyer->transactions()->with('product.categories')->get()
            ->pluck('product.categories')
            ->unique('id')
            ->values()
            ->collapse();

        return $this->responses($category, false, 200);
    }
}
