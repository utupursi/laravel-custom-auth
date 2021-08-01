<?php

namespace App\Console\Commands;

use App\Models\UserToken;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteExpiredTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:delete_expired_tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $userToken = UserToken::where('expires_at', '<=', Carbon::now()->format('Y-m-d h:i:s'));
        if ($userToken) {
            $userToken->delete();
        }
    }
}
