<?php

namespace App\Policies;

use App\User;
use App\Documento;
use Illuminate\Auth\Access\HandlesAuthorization;

class DocumentoPolicy
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
     * Determine whether the user can view the documento.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function view(User $user, Documento $documento)
    {
        //
    }

    /**
     * Determine whether the user can create documentos.
     *
     * @param  \App\User  $user
     * @return mixed
     */
    public function create(User $user)
    {

        return $user->hasRole('Documenta') ?: $this->deny("Acceso denegado. No puede documentar");
    }

    /**
     * Determine whether the user can update the documento.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function update(User $user, Documento $documento)
    {
        return $user->hasRole('Documenta') ?: $this->deny("Acceso denegado. No puede documentar");
    }

    /**
     * Determine whether the user can delete the documento.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function delete(User $user, Documento $documento)
    {
        return $user->hasRole('Documenta') ?: $this->deny("Acceso denegado. No puede documentar");
    }

    /**
     * Determine whether the user can restore the documento.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function restore(User $user, Documento $documento)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the documento.
     *
     * @param  \App\User  $user
     * @param  \App\Documento  $documento
     * @return mixed
     */
    public function forceDelete(User $user, Documento $documento)
    {
        //
    }
}
