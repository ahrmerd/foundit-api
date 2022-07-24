<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Builder;
use App\Http\Resources\BaseResource;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\JsonResource;
use Spatie\QueryBuilder\QueryBuilder;
use Exception;

/**
 * Class BaseRepository.
 */
abstract class BaseRepository
{

    /**
     * The repository model.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;


    /**
     * The repository model resource.
     *
     * @var null|\lluminate\Http\Resources\Json\JsonResource
     */
    protected $resource;

    abstract protected function getFilters();
    abstract protected function getIncludes();
    abstract protected function getSorts();
    /**
     * The query builder.
     *
     * @var \Illuminate\Database\Eloquent\Builder
     */
    protected $query;



    public function setLimit(Builder $query): Builder
    {
        return $query->when(request()->has('limit'), fn (Builder $query) => $query->limit(request()->query('limit')));
    }

    public function setLimitAndOffset(Builder $query)
    {
        return $this->setOffset($this->setLimit($query));
    }

    public function setOffset(Builder $query, $from = 0): Builder
    {
        return $query->when(request()->has('offset'), fn (Builder $query) => $query->offset(request()->query('limit')));
    }

    /**
     * BaseRepository constructor.
     */
    public function __construct()
    {
        $this->makeModel();
        $this->makeResource();
    }

    /**
     * Specify Model class name.
     *
     * @return mixed
     */
    abstract public function model();

    /**
     * @return Model|mixed
     * @throws Exception
     */
    public function makeModel()
    {
        $model = app()->make($this->model());

        if (!$model instanceof Model) {
            throw new Exception("Class {$this->model()} must be an instance of " . Model::class);
        }

        return $this->model = $model;
    }

    public function makeResource()
    {
        $namespace = 'App\Http\Resources';
        try {
            $resourceClass = $namespace . '\\' . class_basename($this->model()) . 'Resource';
            $resourceInstance = new $resourceClass([]);
            if ($resourceInstance instanceof JsonResource) {
                return $this->resource = $resourceClass;
            }
        } catch (\Throwable $th) {
            // throw $th;
        }
        return $this->resource = BaseResource::class;
    }


    /**
     * Get all the model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function all(array $columns = ['*'])
    {

        $models = $this->query->get($columns);

        return $models;
    }

    /**
     * Create a new model record in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * Create one or more new model records in the database.
     *
     * @param array $data
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function createMultiple(array $data)
    {
        $models = new Collection();

        foreach ($data as $d) {
            $models->push($this->create($d));
        }

        return $models;
    }

    /**
     * Delete the specified model record from the database.
     *
     * @param $id
     *
     * @return bool|null
     * @throws \Exception
     */
    public function deleteById($id): bool
    {
        return $this->model->newQuery()->findOrFail($id)->delete();
    }

    /**
     * Delete multiple records.
     *
     * @param array $ids
     *
     * @return int
     */
    public function deleteMultipleById(array $ids): int
    {
        return $this->model->destroy($ids);
    }

    /**
     * Get the first specified model record from the database.
     *
     * @param array $columns
     *
     * @return Model|static
     */
    public function first(array $columns = ['*'])
    {
        $model = $this->query->firstOrFail($columns);
        return new $this->resource($model);
    }

    /**
     * Get all the specified model records in the database.
     *
     * @param array $columns
     *
     * @return Collection|static[]
     */
    public function get(array $columns = ['*'])
    {
        $models = $this->query->get($columns);

        return $models;
    }

    public function getRequestedIncludes()
    {
        $includes = [];
        $allowedIncludes = $this->getIncludes();
        if (request()->has('include')) {
            $requestedIncludes = explode(',',  request()->input('include'));
            foreach ($requestedIncludes as $relationship) {
                if (in_array(strtolower($relationship), $allowedIncludes)) {
                    array_push($includes, $relationship);
                }
            }
        }

        return $includes;
    }

    public function appendIncludes(Builder $query)
    {
        return request()->has('include')  ? $query->with($this->getRequestedIncludes()) : $query;
    }
    /**
     * Get the specified model record from the database.
     *
     * @param       $id
     * @param array $columns
     *
     * @return Collection|Model
     */
    public function getById($id, array $columns = ['*'])
    {
        return new $this->resource($this->appendIncludes($this->model->newQuery())->findOrFail($id));
    }

    public function index(Builder $query = null)
    {
        $total = $this->model->count();
        $query = $query ? $this->setLimitAndOffset($query) : $this->setLimitAndOffset($this->model->query());
        $query = QueryBuilder::for($query)
            ->allowedSorts([...$this->getSorts(), 'id', 'created_at'])
            ->allowedIncludes($this->getIncludes())
            ->allowedFilters($this->getFilters());
        $models = request()->has('page') ? $query->paginate() : $query->get();
        if ($this->resource) {
            return $this->resource::collection(($models))->additional(['Total-Count' => $total]);
        } else {
            return ['data' => $models, 'Total-Count' => $total];
        }
    }

    /**
     * Update the specified model record in the database.
     *
     * @param       $id
     * @param array $data
     * @param array $options
     *
     * @return Collection|Model
     */
    public function updateById($id, array $data, array $options = [])
    {
        $model = $this->model->newQuery()->findOrFail($id);
        $model->update($data, $options);
        return $model;
    }
}
