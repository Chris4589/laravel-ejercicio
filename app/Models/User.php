<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;//add  SoftDeletes
    const USUARIO_VERIFICADO = '1';
    const USUARIO_NO_VERIFICADO = '0';

    const USUARIO_ADMINISTRADOR = 'true';
    const USUARIO_REGULAR = 'false';

    protected $table = 'users';
    protected $dates = ['delete_at'];
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verified_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verified_token'
    ];//datos sensibles - para no traerlos con get

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    //transformar la data
    /* protected $casts = [
        'email_verified_at' => 'datetime',
    ]; */

    public function esVerificado() {
        return $this->verified == User::USUARIO_VERIFICADO;
    }

    public function esAdministrador() {
        return $this->admin == User::USUARIO_ADMINISTRADOR;
    }

    public static function generarToken() {
        return Str::random(40);
    }

    public function setNameAttribute($value) {
        //$attributes viene de la orm
        $this->attributes['name'] = Str::lower($value);
    }

    public function getNameAttribute($value) {
        //$attributes viene de la orm
        return ucwords($value);
    }

    public function setEmailAttribute($value) {
        $this->attributes['email'] = Str::lower($value);
    }
}
