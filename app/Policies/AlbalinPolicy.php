<?php

namespace App\Policies;

use App\User;
use App\Albalin;
use Illuminate\Auth\Access\HandlesAuthorization;

class AlbalinPolicy
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

    public function before($authUser)
    {

               // con esto ya no pasa por ningún otro método, lo dejo por si lo necesito para más adelante.
        if($authUser->hasRole('Admin')){
            return true;
        }
    }

  /**
     * Determine whether the user can create models.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $authUser)
    {
        return $authUser->hasRole('Factura') ?: $this->deny("Acceso denegado. Role de facturación requerido");
    }


}
