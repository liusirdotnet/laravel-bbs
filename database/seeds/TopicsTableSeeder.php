<?php

use App\Models\Category;
use App\Models\User;
use Illuminate\Database\Seeder;
use App\Models\Topic;

class TopicsTableSeeder extends Seeder
{
    public function run()
    {
        $userIds = User::all()->pluck('id')->toArray();
        $categoryIds = Category::all()->pluck('id')->toArray();

        $facker = app(Faker\Generator::class);

        $topics = factory(Topic::class)
            ->times(100)
            ->make()
            ->each(function ($topic, $index) use ($userIds, $categoryIds, $facker) {
                $topic->user_id = $facker->randomElement($userIds);
                $topic->category_id = $facker->randomElement($categoryIds);
            });

        Topic::insert($topics->toArray());
    }
}
