<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Products;
use App\Models\Seller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        //
        $products = $seller->products()->get();
        return $this->responses($products, false, 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, User $seller)
    {
        $rules = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];

        $this->validate($request, $rules);

        //insert
        $data = $request->all();

        $data['status'] = Products::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = $request->image->store('');//laravel detecta que es un archivo y crea el store(), va '' porque se configuro antes
        $data['seller_id'] = $seller->id;

        $product = Products::create($data);

        return $this->responses($product, false, 200);
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Products $product)
    {
        //
        $rules = [
            'quantity' => 'integer|min:1',
            'status' => 'in: ' . Products::PRODUCTO_DISPONIBLE . ',' . Products::PRODUCTO_NO_DISPONIBLE,
            'image' => 'image',
        ];

        $this->validate($request, $rules);

        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));

        if ($request->has('status')) {
            $product->status = $request->status;

            if ($product->estaDisponible() && $product->categories()->count() == 0) {
                return $this->responses('Un producto activo debe tener al menos una categoría', false, 409);
            }
        }

        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            //accede al storage de laravel Storage::

            //guardar el name de la imagen guardada en el storage de laravel
            $product->image = $request->image->store('');
        }


        if ($product->isClean()) {
            return $this->responses('Se debe especificar al menos un valor diferente para actualizar', false, 422);
        }

        $product->save();

        return $this->responses($product, false, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Products $product)
    {
        //
        $this->verificarVendedor($seller, $product);

        Storage::delete($product->image);
        //Storage nos permite interactuar con el sistema de archivo de laravel
        $product->delete();

        return $this->responses($product, false, 200);
    }

    protected function verificarVendedor(Seller $seller, Products $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422, 'El vendedor especificado no es el vendedor real del producto');
        }
    }
}
