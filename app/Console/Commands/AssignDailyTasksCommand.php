<?php

namespace App\Console\Commands;

use App\Models\Task;
use App\Models\User;
use Illuminate\Console\Command;

class AssignDailyTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily-tasks:assign';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign daily tasks for all users';

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
     * @return void
     */
    public function handle()
    {
        $users = User::all();

        foreach ($users as $user)
        {
            $tasksIds = Task::where('user_id', null)->where('solved', 0)->limit(10)->pluck('id');
            Task::whereIn('id', $tasksIds)->update(['user_id', $user->id]);
        }
    }
}
