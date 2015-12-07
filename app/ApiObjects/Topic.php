<?php namespace App\ApiObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;

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
            'votes' => $this->topic->votes()->count()
        ];
    }
}
