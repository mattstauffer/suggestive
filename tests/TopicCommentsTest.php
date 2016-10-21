<?php

use App\Comment;
use App\Topic;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class TopicCommentsTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function test_comments_can_be_created()
    {
        $user = factory(User::class)->create();
        $topic = factory(Topic::class)->create(['user_id' => $user->id]);

        $this->be($user);

        $this->post('api/topics/' . $topic->id . '/comments', [
            'body' => 'Hello world!'
        ]);

        $this->visit('api/topics/' . $topic->id . '/comments');
        $this->seeJson([
            'body' => 'Hello world!',
            'topic_id' => $topic->id,
            'user_id' => $user->id
        ]);
    }
}
