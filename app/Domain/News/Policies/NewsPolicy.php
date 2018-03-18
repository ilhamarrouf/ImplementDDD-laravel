<?php

namespace App\Domain\News\Policies;

use App\Domain\News\Entities\News;
use App\Domain\User\Entities\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class NewsPolicy
{
    use HandlesAuthorization;

    public function update(User $user, News $news) : bool
    {
        return (int) $user->id === (int) $news->creator_id;
    }

    public function delete(User $user, News $news) : bool
    {
        return (int) $user->id === (int) $news->creator_id;
    }
}
