<?php

namespace App\Policies;

use App\User;
use App\Carpeta;
use Illuminate\Auth\Access\HandlesAuthorization;

class CarpetaPolicy
{
    use HandlesAuthorization;

    public function before($user)
    {
            // con esto ya no pasa por ningún otro método, lo dejo por si lo necesito para más adelante.
        if($user->hasRole('Admin')){
            return true;
        }
    }


    /**
     * Determine whether the user can view the carpeta.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function view(User $user, Carpeta $carpeta)
    {
        return true;
    }

    /**
     * Determine whether the user can create carpetas.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {
        return $user->hasRole('Admin') ?: $this->deny("Acceso denegado. Role de Administrador requerido");
    }

    /**
     * Determine whether the user can update the carpeta.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function update(User $user, Carpeta $carpeta)
    {
        return ($user->hasRole('Documenta') || $user->hasRole('Admin')) ?: $this->deny("Acceso denegado. No dispone de permisos (admin-documenta)");
    }

    /**
     * Determine whether the user can delete the carpeta.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function delete(User $user, Carpeta $carpeta)
    {
        return $user->hasRole('Admin') ?: $this->deny("Acceso denegado. Role de Administrador requerido");
    }

    /**
     * Determine whether the user can restore the carpeta.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function restore(User $user, Carpeta $carpeta)
    {
        return $user->hasRole('Admin') ?: $this->deny("Acceso denegado. Role de Administrador requerido");
    }

    /**
     * Determine whether the user can permanently delete the carpeta.
     *
     * @param  \App\User  $user
     * @param  \App\Carpeta  $carpeta
     * @return mixed
     */
    public function forceDelete(User $user, Carpeta $carpeta)
    {
        return $user->hasRole('Admin') ?: $this->deny("Acceso denegado. Role de Administrador requerido");
    }
}
