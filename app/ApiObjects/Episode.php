<?php namespace App\ApiObjects;

use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Auth;

class Episode implements Arrayable, Jsonable
{
    protected $episode;

    public function __construct($episode)
    {
        $this->episode = $episode;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'id' => $this->episode->id,
            'title' => $this->episode->title,
            'number' => $this->episode->number,
            // @todo: Figure out how to turn this off at times?
            'topics' => $this->episode->topics->toArray(),
        ];
    }
}
