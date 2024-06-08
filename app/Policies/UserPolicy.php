<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /**
     * Create a new policy instance.
     */

    public function crudMy(User $user)
    {
        // Allow the user to view the profile if they are not an employee
        return $user->type != 'E';
    }
}
