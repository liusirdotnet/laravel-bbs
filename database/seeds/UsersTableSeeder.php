<?php

use App\Models\User;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = app(\Faker\Generator::class);

        $avatars = [
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/aemooT8dae1Cobu.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Aiqueeyov7aiFaiy.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/ga2zaiZ6tuo8eife.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/aecah3Eo1shahchu.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Cae8ro9Reequae2x.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Eiyoo9ohthie9ahl.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/DeiH5uo6ooTahRat.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/oa0taiPhaeGhu7xi.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Tooz8meeha6gooVu.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/phei9uceey1OhM7d.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Oorooyealai9dobo.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/miegoh5Aibe4quai.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/theXathiiseith6u.jpg',
            'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/chee8oBea2Bahw2y.jpg',
        ];

        $users = factory(User::class)
            ->times(10)
            ->make()
            ->each(function ($user, $index) use ($faker, $avatars) {
                $user->avatar = $faker->randomElement($avatars);
            });
        $array = $users->makeVisible(['password', 'remember_token'])->toArray();

        User::insert($array);

        $user = User::find(1);
        $user->role_id = 1;
        $user->name = 'apphabeter';
        $user->email = 'alphabeter@qq.com';
        $user->avatar = 'http://pbfa6u6aq.bkt.clouddn.com/image/user/avatar/Ji3ohCho5Quov5UL.jpg';
        $user->save();

        // 初始化 1 号用户角色为站长。
        // $user->assignRole('Founder');

        // 初始化 2 号用户角色为网管。
        // $user = User::find(2);
        // $user->assignRole('Webmaster');
    }
}
