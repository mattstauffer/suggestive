<?php

namespace App;

use App\User;
use App\Vote;
use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    const FLAG_ACCEPTED = 'accepted';
    const FLAG_DUPLICATE = 'duplicate';
    const FLAG_REJECTED = 'rejected';

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
            self::FLAG_ACCEPTED,
            self::FLAG_DUPLICATE,
            self::FLAG_REJECTED
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

    public function flagAccepted()
    {
        $this->status = self::FLAG_ACCEPTED;
        $this->save();
    }

    public function flagDuplicate()
    {
        $this->status = self::FLAG_DUPLICATE;
        $this->save();
    }

    public function flagRejected()
    {
        $this->status = self::FLAG_REJECTED;
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
