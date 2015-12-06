<?php

use App\Topic;
use App\User;
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
            'title' => 'How is Laravel 5.2 going?'
        ]);

        $this->visit('api/topics');
        $this->seeJson([
            'title' => 'How is Laravel 5.2 going?'
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
            'title' => $topic1->title
        ]);
        $this->seeJson([
            'title' => $topic2->title
        ]);
    }
}
