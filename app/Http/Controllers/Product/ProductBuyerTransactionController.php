<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Models\Products;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Products $product, User $buyer)
    {
        //
        $rules = [
            'quantity' => 'required|integer|min:1',
        ];

        $this->validate($request, $rules);
        
        if ($buyer->id == $product->seller_id) {
            return $this->responses('El comprador debe ser distito del vendedor', true, 409);
        }

        if (!$buyer->esVerificado()) {
            return $this->responses('debes estar verificado', true, 409);
        }

        if (!$product->seller->esVerificado()) {
            return $this->responses('El vendedor debe ser un usuario verificado', true, 409);
        }

        if (!$product->estaDisponible()) {
            return $this->responses('El producto para esta transacción no está disponible', true, 409);
        }

        if ($product->quantity < $request->quantity) {
            return $this->responses('El producto no tiene la cantidad disponible requerida para esta transacción', true, 409);
        }

        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();

            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'products_id' => $product->id,
            ]);

            return $this->responses($transaction);
        });
    }
}
