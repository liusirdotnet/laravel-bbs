<?php

namespace App\Observers;

use App\Models\Reply;

class ReplyObserver
{
    /**
     * @param \App\Models\Reply $reply
     */
    public function creating(Reply $reply)
    {
        $reply->content = clean($reply->content, 'user_topic_body');
    }

    /**
     * @param \App\Models\Reply $reply
     */
    public function created(Reply $reply)
    {
        $reply->topic->increment('reply_count', 1);
    }
}
