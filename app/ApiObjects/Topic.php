<?php namespace App\ApiObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Auth;

class Topic implements Arrayable, Jsonable
{
    protected $topic;

    public function __construct($topic)
    {
        $this->topic = $topic;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'id' => $this->topic->id,
            'title' => $this->topic->title,
            'description' => $this->topic->description,
            'votes' => $this->topic->votes()->count(),
            'status' => $this->topic->status,
            'episode_id' => $this->topic->episode? $this->topic->episode->id : null,
            'userVotedFor' => in_array($this->topic->id, Auth::user()->votes->lists('topic_id')->toArray()),
            'comment_count' => $this->topic->comments->count()
        ];
    }
}
