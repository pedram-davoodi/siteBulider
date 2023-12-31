<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

abstract class Repository implements RepositoryInterface
{
    protected Model $model;

    protected string $modelClass;

    public function __construct()
    {
        $this->model = app($this->modelClass);
    }

    public function getOneById($id): ?Collection
    {
        return collect($this->model->find($id)->first()?->toArray());
    }

    public function getByIds(array $ids): ?Collection
    {
        return $this->model->find($ids)?->map(fn ($item) => collect($item->toArray()));
    }

    public function getAll(): ?Collection
    {
        return $this->model->all()?->map(fn ($item) => collect($item->toArray()));
    }

    public function paginate(int $paginate): LengthAwarePaginator
    {
        return $this->model->paginate($paginate);
    }

    public function getFirstWhere(...$params): ?Model
    {
        return $this->model->firstWhere(...$params);
    }

    public function getModelClass(): string
    {
        return $this->modelClass;
    }
}
