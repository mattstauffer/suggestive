<?php namespace App\ApiObjects;

use App\ApiObjects\User as ApiUser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Auth;

class Comment implements Arrayable, Jsonable
{
    protected $comment;

    public function __construct($comment)
    {
        $this->comment = $comment;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'id' => $this->comment->id,
            'body' => $this->comment->body,
            'user_id' => (integer) $this->comment->user_id,
            'user' => $this->getUser(),
            'topic_id' => (integer) $this->comment->topic_id,
            'created_at' => $this->comment->created_at,
            'time_ago' => $this->comment->created_at->diffForHumans()
        ];
    }

    public function getUser()
    {
        return (new ApiUser($this->comment->user))->toArray();
    }
}
