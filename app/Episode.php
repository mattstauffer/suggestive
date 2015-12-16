<?php

namespace App;

use App\Topic;
use App\User;
use Exception;
use Illuminate\Database\Eloquent\Model;

class Episode extends Model
{
    protected $fillable = [
        'title',
    ];

    protected $casts = [
        'number' => 'integer'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function topics()
    {
        return $this->hasMany(Topic::class);
    }
}
