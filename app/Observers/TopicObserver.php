<?php

namespace App\Observers;

use App\Handlers\TranslateHandler;
use App\Models\Topic;

// creating, created, updating, updated, saving,
// saved,  deleting, deleted, restoring, restored

class TopicObserver
{
    public function creating(Topic $topic)
    {
        //
    }

    public function updating(Topic $topic)
    {
        //
    }

    /**
     * @param \App\Models\Topic $topic
     */
    public function saving(Topic $topic)
    {
        $topic->body = clean($topic->body, 'user_topic_body');
        $topic->excerpt = make_excerpt($topic->body);

        if (! $topic->slug) {
            $topic->slug = app(TranslateHandler::class)->translate($topic->title);
        }
    }
}
