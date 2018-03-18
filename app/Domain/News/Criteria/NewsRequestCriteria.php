<?php

namespace App\Domain\News\Criteria;

use App\Domain\News\Entities\News;
use Illuminate\Http\Request;
use Prettus\Repository\Contracts\CriteriaInterface;
use Prettus\Repository\Contracts\RepositoryInterface;

/**
 * Class NewsRequestCriteriaCriteria.
 *
 * @package namespace App\Criteria;
 */
class NewsRequestCriteria implements CriteriaInterface
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Apply criteria in query repository
     *
     * @param string              $model
     * @param RepositoryInterface $repository
     *
     * @return mixed
     */
    public function apply($model, RepositoryInterface $repository)
    {
        if ($this->request->has('topic') && !empty($this->request->topic)) {
            $model = $model->whereHas('topics', function ($query) {
                $query->whereId($this->request->topic);
            });
        }

        if ($this->request->has('status') && !empty($this->request->status)) {
            $model = $model->whereStatus($this->request->status);
        }

        return $model;
    }
}
