<?php

namespace App\Http\Controllers\Api;

use App\Domain\Topic\Entities\Topic;
use App\Domain\Topic\Repositories\TopicRepository;
use App\Domain\Topic\Requests\TopicCreateRequest;
use App\Domain\Topic\Requests\TopicUpdateRequest;
use App\Domain\Topic\Resources\TopicResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    protected $topic;

    public function __construct(TopicRepository $topic)
    {
        $this->topic = $topic;
        $this->middleware('auth:api')->only('create', 'update', 'delete');
    }

    public function index(Request $request)
    {
        $topics = $this->topic->paginate($request->paginate);

        return TopicResource::collection($topics);
    }

    public function show(Topic $topic)
    {
        return new TopicResource($topic);
    }

    public function create(TopicCreateRequest $request)
    {
        $topic = $this->topic->create($request->all());

        return new TopicResource($topic);
    }

    public function update(TopicUpdateRequest $request, Topic $topic)
    {
        $topic = $this->topic->update($request->all(), $topic->id);

        return new TopicResource($topic);
    }

    public function delete(Topic $topic)
    {
        $topic->delete();

        return response()->json(['message' => trans('topic.deleted')], 200);
    }
}
