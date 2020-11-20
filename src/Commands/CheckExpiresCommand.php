<?php

namespace Qihucms\Role\Commands;

use Illuminate\Console\Command;

class CheckExpiresCommand extends Command
{

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'role:checkExpires';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'check expires.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $count = \DB::table('role_users')->where('expires', '<', now()->toDateTimeString())->delete();
        $this->info('A total of ' . $count . ' items were deleted.');
    }
}
