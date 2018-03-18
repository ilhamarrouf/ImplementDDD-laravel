<?php

namespace App\Domain\News\Entities;

use App\Domain\Topic\Entities\Topic;
use App\Domain\User\Entities\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphToMany;

class News extends Model
{
    const STATUS_PUBLISH = 1;

    const STATUS_DRAFT = 2;

    const STATUS_DELETE = 3;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'creator_id', 'title', 'slug', 'body', 'status',
    ];

    /**
     * Get the route key for the model.
     *
     * @return string
     */
    public function getRouteKeyName() : string
    {
        return 'slug';
    }

    public function getStatusAttribute($status) : string
    {
        switch ($status) {
            case self::STATUS_PUBLISH:
                return 'publish';
                break;
            case self::STATUS_DRAFT:
                return 'draft';
                break;
            case self::STATUS_DELETE:
                return 'delete';
                break;
        }
    }

    public function setStatusAttribute($status) : void
    {
        switch ($status) {
            case 'publish':
                $this->attributes['status'] = self::STATUS_PUBLISH;
                break;
            case 'draft':
                $this->attributes['status'] = self::STATUS_DRAFT;
                break;
            case 'delete':
                $this->attributes['status'] = self::STATUS_DELETE;
                break;
        }
    }

    public function creator() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function topics() : MorphToMany
    {
        return $this->morphToMany(Topic::class, 'topicable');
    }
}
