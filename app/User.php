<?php

namespace App;

use App\Episode;
use App\Topic;
use App\Vote;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;

class User extends Model implements AuthenticatableContract,
                                    AuthorizableContract,
                                    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword;

    protected $table = 'users';

    protected $fillable = ['name', 'email', 'github_id', 'twitter_id', 'avatar'];

    protected $hidden = ['remember_token'];

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    public function isAdmin()
    {
        return $this->role === 'owner' || $this->role === 'admin';
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function episodes()
    {
        return $this->hasMany(Episode::class);
    }
}
