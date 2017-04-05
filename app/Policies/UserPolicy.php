<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Limit to Physicians.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function index(User $user)
    {
        return $user->isPhysician();
    }

    /**
     * Limit to Physicians.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function create(User $user)
    {
        return $user->isPhysician();
    }

    /**
     * Limit to Physicians.
     *
     * @param  \App\Models\User $user
     * @return boolean
     */
    public function find(User $user)
    {
        return $user->isPhysician();
    }
}
