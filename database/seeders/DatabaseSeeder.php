<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Products;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        // \App\Models\User::factory(10)->create();
        User::truncate(); //borrar datos de la tabla para los factories
        Category::truncate();
        Products::truncate();
        Transaction::truncate();

        DB::table('category_products')->truncate();

        //User::flushEventListeners();

        $cantidadUsuarios = 20;
        $categories = 15;
        $products = 60;
        $transaction = 20;

        User::factory($cantidadUsuarios)->create();
        Category::factory($categories)->create();
        Products::factory($products)->create()->each(
            function($producto) {
                $categorias = Category::all()->random(mt_rand(1, 5))->pluck('id');
                $producto->categories()->attach($categorias);
            }
        );
        Transaction::factory($transaction)->create();
    }
}
