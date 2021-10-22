<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Products;
use Illuminate\Http\Request;

class ProductCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Products $product)
    {
        //
        $category = $product->categories;
        return $this->responses($category);
    }

    public function update(Request $request, Products $product, Category $category)
    {
        //sync, attach, syncWithoutDetaching
        $product->categories()->syncWithoutDetaching([$category->id]);

        return $this->responses($product->categories);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Products $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->responses('La categoría especificada no es una categoría de este producto', 404);
        }

        $product->categories()->detach([$category->id]);

        return $this->responses($product->categories);
    }
}
