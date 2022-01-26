<?php

namespace App\Policies;

use App\User;
use App\Producto;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductoPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

        // esto se ejecuta antes de cualquier método
    public function before($authUser)
    {
            // con esto ya no pasa por ningún otro método, lo dejo por si lo necesito para más adelante.
        if($authUser->hasRole('Root') || $authUser->hasRole('Admin')){
            return true;
        }
    }


    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function update(User $authUser, Producto $producto)
    {

        return $authUser->hasRole('Admin') ?: $this->deny("Acceso denegado, solo un administrador puede actualizar productos");

    }


    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\User  $user
     * @param  \App\User  $model
     * @return mixed
     */
    public function delete(User $authUser, Producto $producto)
    {

        return $authUser->hasRole('Admin') ?: $this->deny("Acceso denegado, solo un administrador puede borrar productos");

    }
}
