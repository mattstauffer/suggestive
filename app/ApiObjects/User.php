<?php namespace App\ApiObjects;

use App\ApiObjects\User as ApiUser;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Facades\Auth;

class User implements Arrayable, Jsonable
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    public function toArray()
    {
        return [
            'id' => $this->user->id,
            'name' => $this->user->name,
            'avatar' => $this->user->avatar
        ];
    }
}
