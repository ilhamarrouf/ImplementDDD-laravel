<?php

namespace App\Domain\User\Observers;

use App\Domain\User\Entities\User;

class UserObserver
{
    public function creating(User $user) : void
    {
        $user->avatar = User::DEFAULT_AVATAR;
    }
}
