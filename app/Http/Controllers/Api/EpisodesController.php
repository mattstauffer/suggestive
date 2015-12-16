<?php

namespace App\Http\Controllers\Api;

use App\ApiObjects\Episode as ApiEpisode;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Episode;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EpisodesController extends Controller
{
    public function index(Request $request)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        return Episode::get()->map(function ($episode) {
            return new ApiEpisode($episode);
        });
    }

    public function store(Request $request)
    {
        if (! Auth::user()->isAdmin()) {
            abort(403);
        }

        $this->validate($request, [
            'number' => 'required|integer',
            'title' => 'required',
        ]);

        $episode = new Episode;
        $episode->title = $request->get('title');
        $episode->number = $request->get('number');

        Auth::user()->episodes()->save($episode);

        return response()->json(new ApiEpisode($episode), 201);
    }

    public function show($id)
    {
        return new ApiEpisode(Episode::findOrFail($id));
    }

    // public function patch($id, Request $request)
    // {
    //     $episode = Episode::findOrFail($id);
    //     $this->authorize('update-episode', $episode);

    //     $episode->patch($request->all());

    //     return response('', 200);
    // }
}
