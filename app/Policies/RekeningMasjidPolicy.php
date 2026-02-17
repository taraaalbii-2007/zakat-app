<?php

namespace App\Policies;

use App\Models\Pengguna;
use App\Models\RekeningMasjid;
use Illuminate\Auth\Access\HandlesAuthorization;

class RekeningMasjidPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(Pengguna $user): bool
    {
        return in_array($user->peran, ['admin_masjid', 'amil']);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(Pengguna $user, RekeningMasjid $rekeningMasjid): bool
    {
        return $user->masjid_id === $rekeningMasjid->masjid_id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(Pengguna $user): bool
    {
        return $user->peran === 'admin_masjid';
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(Pengguna $user, RekeningMasjid $rekeningMasjid): bool
    {
        return $user->masjid_id === $rekeningMasjid->masjid_id && 
               $user->peran === 'admin_masjid';
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(Pengguna $user, RekeningMasjid $rekeningMasjid): bool
    {
        return $user->masjid_id === $rekeningMasjid->masjid_id && 
               $user->peran === 'admin_masjid';
    }
}