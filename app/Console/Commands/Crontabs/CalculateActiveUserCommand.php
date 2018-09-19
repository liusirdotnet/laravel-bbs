<?php

namespace App\Console\Commands\Crontabs;

use App\Models\User;
use Illuminate\Console\Command;

class CalculateActiveUserCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crontab:calculate-active-user';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Calculating active users';

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
    public function handle(User $user)
    {
        $this->info('Start calculating active users...');

        $user->calculateActiveUsers();

        $this->info('End calculating active users.');
    }
}
