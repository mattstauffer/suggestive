<?php

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
}
