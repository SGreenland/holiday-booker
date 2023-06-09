<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Holiday;
use Illuminate\Support\Facades\Log;
use Illuminate\Auth\Access\HandlesAuthorization;

class HolidayPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function viewAny(User $user)
    {
        //
    }

    /**
     * Determine whether the user can view the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function view(User $user, Holiday $holiday)
    {
        //
    }

    /**
     * Determine whether the user can create models.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function create(User $user)
    {
        //
    }

    /**
     * Determine whether the user can update the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function update(User $user, Holiday $holiday)
    {
        //
        return $user->roles()->get()->contains('name', 'admin');
    }

    /**
     * Determine whether the user can delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function delete(User $user, Holiday $holiday)
    {
        //
        return $holiday->user_id === $user->id || $user->roles()->get()->contains('name', 'admin');
    }

    /**
     * Determine whether the user can restore the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function restore(User $user, Holiday $holiday)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the model.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Holiday  $holiday
     * @return \Illuminate\Auth\Access\Response|bool
     */
    public function forceDelete(User $user, Holiday $holiday)
    {
        //
        return $holiday->user_id === $user->id || $user->roles()->get()->contains('name', 'admin');
    }
}
