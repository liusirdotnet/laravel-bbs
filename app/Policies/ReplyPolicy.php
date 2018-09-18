<?php

namespace App\Policies;

use App\Models\Reply;
use App\Models\User;

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
     * @param \App\Models\Reply $reply
     *
     * @return bool
     */
    public function destroy(User $user, Reply $reply)
    {
        return $user->isAuthor($reply) || $user->isAuthor($reply->topic);
    }
}
