<?php

namespace App\Http\Controllers\Api;

use App\ApiObjects\Topic as ApiTopic;
use App\Http\Controllers\Controller;
use App\Http\Requests;
use App\Topic;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TopicsController extends Controller
{
    public function index(Request $request)
    {
        if ($request->get('unflagged')) {
            return $this->unflaggedIndex();
        }

        if ($request->get('status')) {
            return $this->indexByStatus($request->get('status'));
        }

        return Topic::active()->get()->map(function ($topic) {
            return new ApiTopic($topic);
        });
    }

    private function unflaggedIndex()
    {
        return Topic::unflagged()->active()->get()->map(function ($topic) {
            return new ApiTopic($topic);
        });
    }

    private function validateStatus($status)
    {
        if (! Topic::isValidStatus($status)) {
            throw new Exception('Invalid status.');
        }
    }

    private function indexByStatus($status)
    {
        $this->validateStatus($status);

        return Topic::where('status', $status)->active()->get()->map(function ($topic) {
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

        return response('', 201);
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

    public function patch($id, Request $request)
    {
        $topic = Topic::findorFail($id);
        $this->authorize('update-topic', $topic);

        $topic->patch($request->all());

        return response('', 200);
    }
}
