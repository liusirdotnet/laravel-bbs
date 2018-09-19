<?php

namespace App\Models\Traits;

use App\Models\Reply;
use App\Models\Topic;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

trait ActiveUserTrait
{
    protected $users = [];
    protected $topicWeight = 4;
    protected $replyWeight = 1;
    protected $publishDays = 7;
    protected $userNumber = 6;
    protected $cacheKey = 'laravelbbs_active_users';
    protected $cacheExpireInMinutes = 60;

    public function getActiveUsers()
    {
        return Cache::remember($this->cacheKey, $this->cacheExpireInMinutes, function () {
            return $this->handleActiveUsers();
        });
    }

    public function calculateActiveUsers()
    {
        $users = $this->handleActiveUsers();
        $this->cacheActiveUsers($users);
    }

    private function handleActiveUsers()
    {
        $this->calculateTopicScore();
        $this->calculateReplyScore();
        $users = array_sort($this->users, function ($user) {
            return $user['score'];
        });
        $users = array_reverse($users, true);
        $users = array_slice($users, 0, $this->userNumber, true);

        $collect = collect();
        foreach ($users as $uid => $user) {
            $user = $this->find($uid);

            if ($user) {
                $collect->push($user);
            }
        }

        return $collect;
    }

    private function calculateTopicScore()
    {
        $users = Topic::query()
            ->select(DB::raw('user_id,count(*) AS topic_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->publishDays))
            ->groupBy('user_id')
            ->get();

        foreach ($users as $user) {
            $this->users[$user->user_id]['score'] = $user->topic_count * $this->topicWeight;
        }
    }

    private function calculateReplyScore()
    {
        $users = Reply::query()
            ->select(DB::raw('user_id,count(*) AS reply_count'))
            ->where('created_at', '>=', Carbon::now()->subDays($this->publishDays))
            ->groupBy('user_id')
            ->get();

        foreach ($users as $user) {
            $score = $user->reply_count * $this->replyWeight;

            if (isset($this->users[$user->user_id])) {
                $this->users[$user->user_id]['score'] += $score;
            } else {
                $this->users[$user->user_id]['score'] = $score;
            }
        }
    }

    private function cacheActiveUsers($users)
    {
        Cache::put($this->cacheKey, $users, $this->cacheExpireInMinutes);
    }
}
