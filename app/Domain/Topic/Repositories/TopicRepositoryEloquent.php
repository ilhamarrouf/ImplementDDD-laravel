<?php

namespace App\Domain\Topic\Repositories;

use App\Domain\Topic\Entities\Topic;
use App\Domain\Topic\Repositories\TopicRepository;
use Prettus\Repository\Criteria\RequestCriteria;
use Prettus\Repository\Eloquent\BaseRepository;

/**
 * Class NewsRepositoryEloquent.
 *
 * @package namespace App\Repositories;
 */
class TopicRepositoryEloquent extends BaseRepository implements TopicRepository
{
    protected $fieldSearchable = [
        'name' => 'like',
    ];

    /**
     * Specify Model class name
     *
     * @return string
     */
    public function model()
    {
        return Topic::class;
    }

    /**
     * Boot up the repository, pushing criteria
     */
    public function boot()
    {
        $this->pushCriteria(app(RequestCriteria::class));
    }

    public function create(array $data) : Topic
    {
        $topic = $this->model;
        $topic->name = $data['name'];
        $topic->save();

        return $topic;
    }
}
