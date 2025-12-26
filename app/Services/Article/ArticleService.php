<?php

namespace App\Services\Article;

use App\Repositories\Article\Interface\ArticleRepositoryInterface;
use App\Services\BaseService;

class ArticleService extends BaseService {
    protected $repository;

    public function __construct(ArticleRepositoryInterface $repository) {
        $this->repository = $repository;
    }
}