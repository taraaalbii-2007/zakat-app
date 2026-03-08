<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\RekeningLembaga;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekeningLembagaPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin_lembaga', 'amil']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Pengguna $user, RekeningLembaga $rekeningLembaga): bool
    {
        return $user->lembaga_id === $rekeningLembaga->lembaga_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Pengguna $user): bool
    {
        return $user->peran === 'admin_lembaga';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Pengguna $user, RekeningLembaga $rekeningLembaga): bool
    {
        return $user->lembaga_id === $rekeningLembaga->lembaga_id && 
               $user->peran === 'admin_lembaga';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Pengguna $user, RekeningLembaga $rekeningLembaga): bool
    {
        return $user->lembaga_id === $rekeningLembaga->lembaga_id && 
               $user->peran === 'admin_lembaga';
    }
}