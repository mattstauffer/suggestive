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

        $topic2->flagAccepted();

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

    public function test_unflagged_topics_dont_show_up_in_accepted()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $topic1 = factory(Topic::class)->make();

        $user->topics()->save($topic1);

        $this->visit('api/topics?status=accepted');
        $this->dontSeeJson([
            'title' => $topic1->title
        ]);
    }

    public function test_it_can_show_all_accepted_topics()
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

        $topic2->flagAccepted();
        $topic3->flagAccepted();

        $this->visit('api/topics?status=accepted');
        $this->dontSeeJson([
            'title' => $topic1->title
        ]);
        $this->seeJson([
            'title' => $topic2->title
        ]);
        $this->seeJson([
            'title' => $topic3->title
        ]);
    }

    public function test_admins_can_flag_topics_as_accepted()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $topic = factory(Topic::class)->make();

        $user->topics()->save($topic);

        $this->patch('api/topics/' . $topic->id, [
            'status' => 'accepted'
        ]);

        $this->visit('api/topics?status=accepted');
        $this->seeJson([
            'title' => $topic->title
        ]);

        $this->visit('api/topics?unflagged=true');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    public function test_admins_can_flag_topics_as_duplicate()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $topic = factory(Topic::class)->make();

        $user->topics()->save($topic);

        $this->patch('api/topics/' . $topic->id, [
            'status' => 'duplicate'
        ]);

        $this->visit('api/topics?status=duplicate');
        $this->seeJson([
            'title' => $topic->title
        ]);

        $this->visit('api/topics?unflagged=true');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    public function test_admins_can_flag_topics_as_rejected()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $topic = factory(Topic::class)->make();

        $user->topics()->save($topic);

        $this->patch('api/topics/' . $topic->id, [
            'status' => 'rejected'
        ]);

        $this->visit('api/topics?status=rejected');
        $this->seeJson([
            'title' => $topic->title
        ]);

        $this->visit('api/topics?unflagged=true');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }
}
