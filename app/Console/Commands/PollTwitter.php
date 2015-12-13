<?php

namespace App\Console\Commands;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Jobs\PollTwitterForUsersHashtag;
use App\User;
use Illuminate\Console\Command;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Support\Facades\Log;

class PollTwitter extends Command
{
    use DispatchesJobs;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'suggestive:poll-twitter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Polls Twitter for any tweets with the configured hashtag.';

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
     * @param  TwitterOAuth  $twitter
     *
     * @return mixed
     */
    public function handle(TwitterOAuth $twitter)
    {
        $users = User::where('role', 'owner')
                     ->whereNotNull('hashtag')
                     ->get(['id', 'name', 'hashtag', 'last_tweet_id']);

        Log::info('Found owners to poll Twitter for', ['count' => $users->count()]);

        $users->each(function ($user) use ($twitter) {
            // Queue a job to handle (potentially) locking HTTP requests on the Twitter API.
            $this->dispatch(new PollTwitterForUsersHashtag($user, $twitter));
        });
    }
}
