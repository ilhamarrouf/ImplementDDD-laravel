<?php

namespace App\Domain\News\Repositories;

use App\Domain\News\Criteria\NewsRequestCriteria;
use App\Domain\News\Entities\News;
use App\Domain\News\Repositories\NewsRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class NewsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class NewsRepositoryEloquent extends BaseRepository implements NewsRepository
{
    protected $fieldSearchable = [
        'title' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return News::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
        $this->pushCriteria(app(NewsRequestCriteria::class));
    }

    public function create(array $data) : News
    {
        $news = $this->model;
        $news->creator()->associate($data['creator'] ?? auth()->id());
        $news->title = $data['title'];
        $news->body = $data['body'];
        $news->status = $data['status'];
        $news->save();

        if (isset($data['topics']) && is_array($data['topics'])) {
            $news->topics()->sync($data['topics']);
        }

        return $news->load('topics');
    }

    public function update(array $data, $id) : News
    {
        $news = $this->model->find($id);
        $news->title = $data['title'];
        $news->body = $data['body'];
        $news->status = $data['status'];
        $news->save();

        if (isset($data['topics']) && is_array($data['topics'])) {
            $news->topics()->sync($data['topics']);
        }

        return $news->load('topics');
    }

    public function delete($id)
    {
        $news = $this->model->find($id);
        $news->status = 'delete';
        $news->save();

        return $news;
    }
}
