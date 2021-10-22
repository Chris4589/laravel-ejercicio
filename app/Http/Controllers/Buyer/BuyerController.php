<?php

namespace App\Http\Controllers\Buyer;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    
    public function index()
    {
        //mostrar lista de compradores del sistema
        $compradores = Buyer::has('transactions')->get();

        return $this->responses($compradores, false, 200);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Buyer $buyer)
    {
        //selec from id
        /* $compradores = Buyer::has('transactions')->findOrFail($id); */

        return $this->responses($buyer, false, 200);
    }
}
