<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
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

    public function create(User $user)
    {
        return $user->role === 'admin';
    }

    public function update(User $user, User $targetUser)
    {
        return $user->role === 'admin' || $user->id === $targetUser->id;
    }

    public function delete(User $user)
    {
        return $user->role === 'admin';
    }
}
