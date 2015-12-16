<?php

use App\Episode;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminEpisodeTest extends TestCase
{
    use DatabaseMigrations;
    use WithoutMiddleware;

    public function test_it_can_show_all_episodes()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $episode1 = factory(Episode::class)->make();
        $episode2 = factory(Episode::class)->make();

        $user->episodes()->save($episode1);
        $user->episodes()->save($episode2);

        $this->visit('api/episodes');
        $this->seeJson([
            'title' => $episode1->title,
            'number' => $episode1->number,
        ]);
        $this->seeJson([
            'title' => $episode2->title,
            'number' => $episode2->number,
        ]);
    }

    public function test_non_admins_cannot_see_episodes()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $admin = factory(User::class, 'admin')->create();
        $episode1 = factory(Episode::class)->make();
        $admin->episodes()->save($episode1);

        $this->get('api/episodes');
        $this->assertResponseStatus(403);
    }

    public function test_admins_can_create_episodes()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $this->post('api/episodes', [
            'number' => 15,
            'title' => 'Our greatest episode yet',
        ]);

        $this->visit('api/episodes');
        $this->seeJson([
            'number' => 15,
            'title' => 'Our greatest episode yet',
        ]);
    }

    public function test_non_admins_cannot_create_episodes()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->post('api/episodes', [
            'number' => 16,
            'title' => 'Our bestest episode yet',
        ]);
        $this->assertResponseStatus(403);
    }
}
