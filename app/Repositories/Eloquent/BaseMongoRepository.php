<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Eloquent\Interface\EloquentMongoRepositoryInterface;
use MongoDB\Laravel\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Exception;

class BaseMongoRepository implements EloquentMongoRepositoryInterface {
    protected $model;

    public function __construct(Model $model) {
        $this->model = $model;
    }

    public function columsQuery($query, array $params) {

        $query->select($this->model->getTable() . '.*');

        if (!empty($params['columns'])) $query->select(explode(",", $params['columns']));

        if (isset($params['order']) && isset($params['sort'])) $query->orderBy($params['order'], $params['sort']);

        return $query;
    }

    public function paginateQuery($query, $params) {

        if (isset($params['length'])) $query->paginate($params['length']);

        return $query;
    }


    public function search(array $params): Collection {
        $query = $this->model->newQuery();

        $query = $this->columsQuery($query, $params);

        $query = $this->paginateQuery($query, $params);

        return $query->get();
    }

    public function searchCount(array $params): int {
        $query = $this->model->newQuery();

        $query = $this->columsQuery($query, $params);

        return $query->count();
    }

    public function findById(string $id): ?Model {
        return $this->model->findOrFail($id);
    }

    public function findAll(): Collection {
        return $this->model->all();
    }

    public function create(array $attributes): Model {
        return $this->model->create($attributes);
    }

    public function insert(array $attributes): bool {
        return $this->model->insert($attributes);
    }

    public function update(string $id, array $attributes): Model {
        $model = $this->findById($id);

        if (empty($model)) throw new Exception("查無資訊");

        $isUpdated = $model->update($attributes);

        if ($isUpdated) return $model;

        throw new Exception("更新失敗");
    }

    public function delete(string $id): bool {
        $model = $this->findById($id);

        if (empty($model)) throw new Exception("查無資訊");

        return $model->delete();
    }
}