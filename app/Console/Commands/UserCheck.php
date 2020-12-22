<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class UserCheck extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:expire';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the active User expire date is validate';

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
        $users = User::all();
        $days = now()->format('Y-m-d');
        foreach ($users as $user)
        {
            if ($days>$user->expire) {
                $user->status = 0;
                $user->save();
            }
        }
    }
}
