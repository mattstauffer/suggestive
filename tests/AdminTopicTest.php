<?php

use App\Topic;
use App\User;
use App\Vote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminTopicTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function test_it_can_show_all_unflagged_topics()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->be($user);

        $topic1 = factory(Topic::class)->make();
        $topic2 = factory(Topic::class)->make();
        $topic3 = factory(Topic::class)->make();

        $user->topics()->save($topic1);
        $user->topics()->save($topic2);
        $user2->topics()->save($topic3);

        $topic2->flagYes();

        $this->visit('api/topics?unflagged=true');
        $this->seeJson([
            'title' => $topic1->title
        ]);
        $this->dontSeeJson([
            'title' => $topic2->title
        ]);
        $this->seeJson([
            'title' => $topic3->title
        ]);
    }

    public function test_it_can_show_all_yes_topics()
    {
        $user = factory(User::class)->create();
        $user2 = factory(User::class)->create();
        $this->be($user);

        $topic1 = factory(Topic::class)->make();
        $topic2 = factory(Topic::class)->make();
        $topic3 = factory(Topic::class)->make();

        $user->topics()->save($topic1);
        $user->topics()->save($topic2);
        $user2->topics()->save($topic3);

        $topic2->flagYes();

        $this->visit('api/topics?status=yes');
        $this->dontSeeJson([
            'title' => $topic1->title
        ]);
        $this->seeJson([
            'title' => $topic2->title
        ]);
        $this->dontSeeJson([
            'title' => $topic3->title
        ]);
    }
}
