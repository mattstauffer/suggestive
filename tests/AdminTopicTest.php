<?php

use App\Topic;
use App\User;
use App\Vote;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminTopicTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_can_show_all_suggested_topics()
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

        $this->visit('api/topics?status=suggested');
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

    /** @test */
    function suggested_topics_dont_show_up_in_accepted()
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

    /** @test */
    function it_can_show_all_accepted_topics()
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

    /** @test */
    function admins_can_flag_topics_as_accepted()
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

        $this->visit('api/topics?status=suggested');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    /** @test */
    function admins_can_flag_topics_as_duplicate()
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

        $this->visit('api/topics?status=suggested');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    /** @test */
    function admins_can_flag_topics_as_rejected()
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

        $this->visit('api/topics?status=suggested');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    /** @test */
    function admins_can_archive_topics()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $topic = factory(Topic::class)->make();

        $user->topics()->save($topic);

        $topic->flagAccepted();

        $this->patch('api/topics/' . $topic->id, [
            'archived' => true
        ]);

        $this->visit('api/topics?status=accepted');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);

        $this->visit('api/topics?status=suggested');
        $this->dontSeeJson([
            'title' => $topic->title
        ]);
    }

    /** @test */
    function admin_can_suggest_a_topic_and_it_will_be_accepted()
    {
        $this->be(factory(User::class, 'admin')->create());

        $this->post('api/topics', [
            'title' => 'How is Laravel 5.4 going?',
            'description' => 'Just because I am curious'
        ]);

        $this->visit('api/topics');
        $this->seeJson([
            'title' => 'How is Laravel 5.4 going?',
            'description' => 'Just because I am curious',
            'status' => 'accepted'
        ]);
    }
}
