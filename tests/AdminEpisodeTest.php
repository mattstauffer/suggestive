<?php

use App\Episode;
use App\Topic;
use App\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithoutMiddleware;

class AdminEpisodeTest extends TestCase
{
    use DatabaseMigrations, WithoutMiddleware;

    /** @test */
    function it_can_show_all_episodes()
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

    /** @test */
    function non_admins_cannot_see_episodes()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $admin = factory(User::class, 'admin')->create();
        $episode1 = factory(Episode::class)->make();
        $admin->episodes()->save($episode1);

        $this->get('api/episodes');
        $this->assertResponseStatus(403);
    }

    /** @test */
    function admins_can_create_episodes()
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

    /** @test */
    function non_admins_cannot_create_episodes()
    {
        $user = factory(User::class)->create();
        $this->be($user);

        $this->post('api/episodes', [
            'number' => 16,
            'title' => 'Our bestest episode yet',
        ]);
        $this->assertResponseStatus(403);
    }

    /** @test */
    function admins_can_schedule_a_topic_for_an_episode()
    {
        $user = factory(User::class, 'admin')->create();
        $this->be($user);

        $episode = factory(Episode::class)->make();
        $user->episodes()->save($episode);
        $episodeId = $episode->id;

        $topic = factory(Topic::class)->make();
        $topic2 = factory(Topic::class)->make();
        $user->topics()->saveMany([$topic, $topic2]);
        $topicId = $topic2->id;

        // @todo: Assert that topic2 is present in the accepted topics list

        $this->post("api/episodes/{$episodeId}/scheduled-topics", [
            'topic_id' => $topicId,
        ]);

        $this->visit("api/episodes/{$episodeId}/scheduled-topics");
        $this->seeJson([
            'id' => $topicId
        ]);

        // @todo: Assert that topic2 is not present in the accepted topics list

        // POST /api/episodes/{id}/scheduled-topics ['topic_id': 5]
        // See: GET /api/episodes/{id}/schedule-topics : topic_id: 5
    }
}
