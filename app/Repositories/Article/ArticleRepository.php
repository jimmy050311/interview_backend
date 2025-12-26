<?php

namespace App\Repositories\Article;

use App\Models\Article;
use App\Repositories\Article\Interface\ArticleRepositoryInterface;
use App\Repositories\Eloquent\BaseRepository;

class ArticleRepository extends BaseRepository implements ArticleRepositoryInterface {
    protected $model;

    public function __construct(Article $model) {
        $this->model = $model;
    }

    public function columsQuery($query, array $params) {
        $query->select($this->model->getTable() . '.*');

        if (!empty($params['columns'])) $query->select(explode(",", $params['columns']));

        if (isset($params['title']) && !blank($params['title'])) $query->where('title', 'like', "%" . $params['title'] . "%");

        if (isset($params['status']) && !blank($params['status'])) $query->where('status', $params['status']);

        if (isset($params['order']) && isset($params['sort'])) $query->orderBy($params['order'], $params['sort']);

        return $query;
    }

}