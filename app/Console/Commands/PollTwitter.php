<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class PollTwitter extends Command
{
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
     * @return mixed
     */
    public function handle()
    {
        $users = User::where('role', 'owner')
                     ->whereNotNull('hashtag')
                     ->get(['id', 'name', 'hashtag', 'last_tweet_id']);

        if ($users) {
            Log::info('Found owners to poll Twitter for', ['count' => $users->count()]);

            $twitter = app('TwitterClient');

            $users->each(function ($user) use ($twitter) {
                Log::info('Polling Twitter', ['owner' => $user->name, 'hashtag' => $user->hashtag]);

                $response = $twitter->get('search/tweets', $this->getParametersForUser($user));

                Log::info('Found statuses for owner on Twitter', ['owner' => $user->name, 'count' => count($response->statuses)]);

                $statuses = collect($response->statuses)->reject(function ($status) {
                    // Reject any quoted or retweeted tweets, to limit duplication
                    return isset($status->quoted_status) || isset($status->retweeted_status);
                });

                $statuses->each(function ($status) use ($user) {
                    $user->topics()->create(['title' => $status->text]);
                });

                if ($most_recent = $statuses->first()) {
                    $user->update(['last_tweet_id' => $most_recent->id_str]);

                    Log::info('Set last tweet for owner', ['owner' => $user->name, 'last_tweet_id' => $most_recent->id_str]);
                }
            });
        };
    }


    /**
     * For the given user, return an array of parameters to query Twitter for.
     *
     * @param  User  $user
     *
     * @return array
     */
    private function getParametersForUser(User $user)
    {
        $parameters = ['q' => $user->hashtag];

        if (! is_null($user->last_tweet_id)) {
            $parameters['since_id'] = $user->last_tweet_id;
        }

        return $parameters;
    }
}
