<?php

namespace App\Console\Commands\Manuals;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class GenerateTokenCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'manual:generate-token';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generating user token';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $userId = $this->ask('请输入用户 ID');
        $user = User::find($userId);

        if (! $user) {
            return $this->error('用户不存在');
        }

        $ttl = 365 * 24 * 60;
        $this->info(Auth::guard('api')->setTTL($ttl)->fromUser($user));
    }
}
