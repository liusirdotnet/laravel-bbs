<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Topic;

class TopicPolicy extends Policy
{
    /**
     * @param \App\Models\User  $user
     * @param \App\Models\Topic $topic
     *
     * @return bool
     */
    public function update(User $user, Topic $topic)
    {
        return $topic->user_id === $user->id;
    }

    public function destroy(User $user, Topic $topic)
    {
        return true;
    }
}
