<?php

namespace App\Jobs;

use App\Handlers\TranslateHandler;
use App\Models\Topic;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\DB;

class TranslateJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var \App\Models\Topic
     */
    protected $topic;

    /**
     * Create a new job instance.
     *
     * @param \App\Models\Topic $topic
     */
    public function __construct(Topic $topic)
    {
        $this->topic = $topic;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $slug = app(TranslateHandler::class)->translate($this->topic->title);
        DB::table('topics')
          ->where('id', $this->topic->id)
          ->update(['slug' => $slug]);
    }
}
