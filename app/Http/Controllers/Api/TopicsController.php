<?php

namespace App\Http\Controllers\Api;

use App\ApiObjects\Topic as ApiTopic;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Topic;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('unflagged')) {
            return $this->unflaggedIndex();
        }

        return Topic::all()->map(function ($topic) {
            return new ApiTopic($topic);
        });
    }

    private function unflaggedIndex()
    {
        return Topic::unflagged()->get()->map(function ($topic) {
            return new ApiTopic($topic);
        });
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $this->validate($request, [
            'title' => 'required'
        ]);

        $topic = new Topic;
        $topic->title = $request->get('title');
        Auth::user()->topics()->save($topic);

        return response()->json([
            'success' => true,
            'message' => 'Topic successfully saved.'
        ]);
    }

    public function show($id)
    {
        return new ApiTopic(Topic::findOrFail($id));
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}
