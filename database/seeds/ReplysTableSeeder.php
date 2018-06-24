<?php

use App\Models\Reply;
use App\Models\Topic;
use App\Models\User;
use Faker\Generator;
use Illuminate\Database\Seeder;

class ReplysTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $topicIds = Topic::all()->pluck('id')->toArray();
        $userIds = User::all()->pluck('id')->toArray();

        $faker = app(Generator::class);
        $replies = factory(Reply::class)
            ->times(1000)
            ->make()
            ->each(function ($reply, $index) use ($topicIds, $userIds, $faker) {
                $reply->topic_id = $faker->randomElement($topicIds);
                $reply->user_id = $faker->randomElement($userIds);
            });
        Reply::insert($replies->toArray());
    }
}
