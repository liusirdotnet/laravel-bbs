<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Reply;

class ReplyPolicy extends Policy
{
    /**
     * @param \App\Models\User  $user
     * @param \App\Models\Reply $topic
     *
     * @return bool
     */
    public function update(User $user, Reply $topic)
    {
        return $user->isAuthor($topic);
    }

    /**
     * @param \App\Models\User  $user
     * @param \App\Models\Reply $topic
     *
     * @return bool
     */
    public function destroy(User $user, Reply $topic)
    {
        return $user->isAuthor($topic);
    }
}
