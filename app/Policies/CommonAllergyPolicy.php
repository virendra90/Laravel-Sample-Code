<?php

namespace App\Policies;

use App\Models\User;

class CommonAllergyPolicy
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
}
