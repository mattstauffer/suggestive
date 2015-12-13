<?php

namespace App\Jobs;

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Jobs\Job;
use App\User;
use Carbon\Carbon;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Response;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class PollTwitterForUsersHashtag extends Job implements SelfHandling, ShouldQueue
{
    use SerializesModels, InteractsWithQueue;

    /**
     * @var User
     */
    protected $user;

    /**
     * @var TwitterOAuth
     */
    protected $twitter;


    /**
     * Create a new job instance.
     *
     * @param User         $user
     * @param TwitterOAuth $twitter
     */
    public function __construct(User $user, TwitterOAuth $twitter)
    {
        $this->user    = $user;
        $this->twitter = $twitter;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('Polling Twitter for new statuses', ['owner' => $this->user->name, 'hashtag' => $this->user->hashtag]);

        $response = $this->twitter->get('search/tweets', $this->getParametersForQuery());

        if ($this->didHitRateLimit()) {
            // Rather than requeuing this job, then having it endlessly poll and
            // hit the API rate limit, delete the job. The next scheduled run
            // of the main task will add a fresh job instance to the queue.
            $this->delete();

            return false;
        }

        $statuses = $this->getTweetsFromResponse($response);

        if ($statuses->isEmpty()) {
            Log::info('No new statuses found on Twitter', ['owner' => $this->user->name]);

            return true;
        }

        Log::info('Found new statuses for owner on Twitter', ['owner' => $this->user->name, 'count' => $statuses->count()]);

        $statuses->each(function ($status) {
            $this->user->topics()->create(['title' => $status->text]);
        });

        $this->setLastTweetForUser($statuses->first());

        return true;
    }


    /**
     * Determine if the last request to the Twitter API hit a rate limit.
     *
     * @return bool
     */
    private function didHitRateLimit()
    {
        $hit_limit = $this->twitter->getLastHttpCode() == Response::HTTP_TOO_MANY_REQUESTS;
        $x_headers = $this->twitter->getLastXHeaders();

        Log::info('Rate limit', [
            'reached'   => $hit_limit,
            'limit'     => $x_headers['x_rate_limit_limit'],
            'remaining' => $x_headers['x_rate_limit_remaining'],
            'reset'     => Carbon::createFromTimestamp($x_headers['x_rate_limit_reset']),
        ]);

        return $hit_limit;
    }


    /**
     * Return a collection of new tweets, rejecting any quoted or retweeted tweets, to limit duplication.
     *
     * @param  array|object  $response
     *
     * @return \Illuminate\Support\Collection
     */
    private function getTweetsFromResponse($response)
    {
        return collect($response->statuses)->reject(function ($status) {
            return isset($status->quoted_status) || isset($status->retweeted_status);
        });
    }


    /**
     * Update the user with the most recent tweet's identifier for future polls against the API.
     *
     * @param  object  $most_recent
     */
    private function setLastTweetForUser($most_recent)
    {
        $this->user->update(['last_tweet_id' => $most_recent->id_str]);

        Log::info('Set last tweet', ['owner' => $this->user->name, 'last_tweet_id' => $most_recent->id_str]);
    }


    /**
     * For the given user, return an array of parameters to query Twitter for.
     *
     * @return array
     */
    private function getParametersForQuery()
    {
        $parameters = ['q' => $this->user->hashtag];

        if (! is_null($this->user->last_tweet_id)) {
            $parameters['since_id'] = $this->user->last_tweet_id;
        }

        return $parameters;
    }
}
