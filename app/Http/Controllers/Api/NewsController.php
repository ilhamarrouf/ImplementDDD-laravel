<?php

namespace App\Http\Controllers\Api;

use App\Domain\News\Entities\News;
use App\Domain\News\Repositories\NewsRepository;
use App\Domain\News\Requests\NewsCreateRequest;
use App\Domain\News\Requests\NewsUpdateRequest;
use App\Domain\News\Resources\NewsResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class NewsController extends Controller
{
    protected $news;

    public function __construct(NewsRepository $news)
    {
        $this->news = $news;
        $this->middleware('auth:api')->only('create', 'update', 'delete');
    }

    public function index(Request $request)
    {
        $news = $this->news->paginate($request->paginate);

        return NewsResource::collection($news);
    }

    public function show(News $news)
    {
        $news = $this->news->find($news->id);

        return new NewsResource($news);
    }

    public function create(NewsCreateRequest $request)
    {
        $news = $this->news->create($request->all());

        return new NewsResource($news);
    }

    public function update(NewsUpdateRequest $request, News $news)
    {
        $this->authorize('update', $news);

        $news = $this->news->update($request->all(), $news->id);

        return new NewsResource($news);
    }

    public function delete(News $news)
    {
        $this->authorize('delete', $news);

        $this->news->delete($news->id);

        return response()->json(['message' => trans('news.deleted')], 200);
    }
}
