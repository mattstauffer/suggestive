<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'user_id',
        'topic_id',
        'body'
    ];

    public function topic()
    {
        return $this->belongsTo(Job::class);
    }
}
