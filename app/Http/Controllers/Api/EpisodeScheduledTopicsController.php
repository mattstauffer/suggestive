<?php

namespace App\Http\Controllers\Api;

use App\Episode;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodeScheduledTopicsController extends Controller
{
    public function index($episodeId)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        return Episode::findOrFail($episodeId)->topics;
    }

    public function store($episodeId, Request $request)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $episode = Episode::findOrFail($episodeId);
        $topic_ids = $this->arrayWrap($request->get('topic_id'));
        $topics = Topic::whereIn('id', $topic_ids)->get();
        $episode->topics()->saveMany($topics);
    }

    private function arrayWrap($var)
    {
        return is_array($var) ? $var : [$var];
    }
}
