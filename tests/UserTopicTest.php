<?php

use App\Topic;
use App\User;
use App\Vote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class UserTopicTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function test_user_can_suggest_a_topic()
    {
        $this->be(factory(User::class)->create());

        $this->post('api/topics', [
            'title' => 'How is Laravel 5.2 going?',
            'description' => 'Just because I am curious'
        ]);

        $this->visit('api/topics');
        $this->seeJson([
            'title' => 'How is Laravel 5.2 going?',
            'description' => 'Just because I am curious'
        ]);
    }

    public function test_upon_creating_a_topic_return_contains_all_relevant_data()
    {
        $this->be(factory(User::class)->create());

        $this->post('api/topics', [
            'title' => 'How is Laravel 5.2 going?',
            'description' => 'Just because I am curious'
        ]);

        $this->seeJson([
            'title' => 'How is Laravel 5.2 going?',
            'description' => 'Just because I am curious',
            'userVotedFor' => false, // Hm, curious.
            'votes' => 0
        ]);
    }

    public function test_user_can_see_list_of_suggested_topics()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $topic1 = factory(Topic::class)->make();
        $topic2 = factory(Topic::class)->make();

        $user->topics()->save($topic1);
        $user->topics()->save($topic2);

        $this->visit('api/topics');
        $this->seeJson([
            'title' => $topic1->title,
            'description' => $topic1->description,
        ]);
        $this->seeJson([
            'title' => $topic2->title,
            'description' => $topic1->description,
        ]);
    }

    public function test_user_can_upvote_a_topic()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $topic1 = factory(Topic::class)->make();
        $user->topics()->save($topic1);

        $this->post('api/topics/' . $topic1->id . '/votes');

        $this->visit('api/topics/' . $topic1->id);
        $this->seeJson([
            'votes' => 1
        ]);
    }

    public function test_users_cant_flag_topics()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $topic = factory(Topic::class)->make();

        $user->topics()->save($topic);

        $this->patch('api/topics/' . $topic->id, [
            'status' => 'accepted'
        ]);

        $this->visit('api/topics?status=accepted');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);

        $this->visit('api/topics?unflagged=true');
        $this->seeJson([
            'title' => $topic->title
        ]);
    }
}
