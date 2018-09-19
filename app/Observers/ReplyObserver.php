<?php

namespace App\Observers;

use App\Models\Reply;
use App\Notifications\ReplyNotification;

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
        $topic = $reply->topic;
        $reply->topic->increment('reply_count', 1);

        // 通知作者话题被回复了。
        if (! $reply->user->isAuthor($topic)) {
            $topic->user->notify(new ReplyNotification($reply));
        }
    }

    /**
     * @param \App\Models\Reply $reply
     */
    public function deleted(Reply $reply)
    {
        $reply->topic->decrement('reply_count', 1);
    }
}
