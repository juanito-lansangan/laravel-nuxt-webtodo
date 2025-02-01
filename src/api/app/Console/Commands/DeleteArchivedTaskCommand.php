<?php

namespace App\Console\Commands;

use App\Models\Task;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class DeleteArchivedTaskCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:delete-archived-task';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete archived tasks that is 1 week older';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $weekInterval = now()->subWeek();

        Task::where('archived_at', '<=', $weekInterval)->delete();
    }
}