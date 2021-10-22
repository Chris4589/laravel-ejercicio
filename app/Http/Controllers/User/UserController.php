<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class UserController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //listara todos los recursos disponibles en ese momento
        $users = User::all(); //select * from

        $page = LengthAwarePaginator::resolveCurrentPage();
        $perPage = 2;

        $results = $users->slice(($page -1) * $perPage, $perPage)->values();
        $paginated = new LengthAwarePaginator($results, $users->count(), $perPage, $page, [
            'path' => LengthAwarePaginator::resolveCurrentPage(),
        ]);

        $paginated->appends(request()->all());
        return $this->responses($paginated, false, 200);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //crear instancias de usuarios
        //insert into
        $rules = [
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed'
        ];

        $this->validate($request, $rules);//para validar

        $campos = $request->all();//todos los campos

        $campos['password'] = bcrypt($request->password);
        $campos['verified'] = User::USUARIO_NO_VERIFICADO;
        $campos['verified_token'] = User::generarToken();
        $campos['admin'] = User::USUARIO_REGULAR;

        $user = User::create($campos);
        return $this->responses($user, false, 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    /* public function show($id) */
    public function show(User $user)//inyeccion de modelos
    {
        //mostrar un usuario en especifico apartir del ID
        /* $user = User::findOrFail($id); */
        //$user = User::find($id);
        /* if (!$user) {
            return $this->responses('No existe el usuario', true, 404);
        } */
        return $this->responses($user, false, 200);
    }

    

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user/* $id */)//inyeccion de mdl
    {
        /* $user = User::findOrFail($id); */

        //actualizar datos
        //update set
        $rules = [
            'email' => 'email|unique:users,email,' . $user->id,
            'password' => 'min:6|confirmed',
            'admin' => 'in:' . User::USUARIO_ADMINISTRADOR . ',' . User::USUARIO_REGULAR
        ];

        $this->validate($request, $rules);//para validar

        if ($request->has('name')) {
            $user->name = $request->name;
        }
        if ($request->has('email')) {
            if ($user->email !== $request->email) {
                $user->email = $request->email;
                $user->verified = User::USUARIO_NO_VERIFICADO;
                $user->verified_token = User::generarToken();
            }
        }

        if ($request->has('password')) {
            $user->password = bcrypt($request->password);
        }

        if ($request->has('admin')) {
            if (!$user->esVerificado()) {
                return $this->responses('Unicamente los usuarios pueden cambiar su valor de administrador', true, 409);
            }
            $user->admin = $request->admin;
        }

        /* if ($user->isDirty()) {
            return $this->responses('Se debe especificar al menos un valor dif', true, 409);
        } */

        $user->save();
        return $this->responses($user, false, 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user/* $id */)
    {
        //delete from
        /* $user = User::findOrFail($id); */

        $user->delete();
        return $this->responses($user, false, 200);
    }

    public function verify($token)
    {
        $user = User::where('verified_token', $token)->firstOrFail();

        $user->verified = User::USUARIO_VERIFICADO;
        $user->verified_token = null;

        $user->save();

        return $this->responses('La cuenta ha sido verificada');
    }
}
