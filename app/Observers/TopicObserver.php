<?php

namespace App\Observers;

use App\Handlers\TranslateHandler;
use App\Jobs\TranslateJob;
use App\Models\Topic;
use Illuminate\Support\Facades\DB;

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
            // TODO: 异步执行消耗时间的任务。
            // $topic->slug = app(TranslateHandler::class)->translate($topic->title);
        }
    }

    /**
     * @param \App\Models\Topic $topic
     */
    public function saved(Topic $topic)
    {
        if (! $topic->slug) {
            TranslateJob::dispatch($topic)->delay(now()->addSecond(3));
        }
    }

    /**
     * @param \App\Models\Topic $topic
     */
    public function deleted(Topic $topic)
    {
        DB::table('replies')->where('topic_id', $topic->id)->delete();
    }
}
