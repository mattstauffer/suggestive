<?php

namespace App;

use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    const FLAG_YES = 'yes';
    const FLAG_DUPLICATE = 'duplicate';

    protected $fillable = [
        'title'
    ];

    protected $patchable = [
        'title',
        'status'
    ];

    public static function validStatuses()
    {
        return [
            self::FLAG_YES,
            self::FLAG_DUPLICATE,
        ];
    }

    public static function isValidStatus($status)
    {
        return $status === null || in_array($status, self::validStatuses());
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function flagYes()
    {
        $this->status = self::FLAG_YES;
        $this->save();
    }

    public function scopeUnflagged($query)
    {
        return $query->where('status', null);
    }

    public function patch($properties)
    {
        foreach ($properties as $key => $value) {
            if (! in_array($key, $this->patchable)) {
                throw new Exception("Cannot patch {$key}");
            }

            $this->$key = $value;
        }

        $this->save();
    }
}
