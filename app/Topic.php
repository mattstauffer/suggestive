<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    protected $fillable = [
        'title'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
