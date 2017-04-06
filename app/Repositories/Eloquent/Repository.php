<?php

namespace App\Repositories\Eloquent;

use App\Repositories\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Container\Container as App;

abstract class Repository implements RepositoryInterface
{
    /**
     * @var App
     */
    private $app;

    /**
     * @var Model
     */
    protected $model;

    /**
     * @param App $app
     */
    public function __construct(App $app)
    {
        $this->app = $app;
        $this->makeModel();
    }

    /**
     * @return mixed
     */
    abstract function model();

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->model->all();
    }

    /**
     * All in order
     *
     * @return mixed
     */
    public function byOrder()
    {
        $this->makeModel();
        return $this->model->orderBy("status")
                           ->orderByDesc("priority")
                           ->orderByDesc("updated_at")
                           ->get();
    }
    
    /**
     * @param array $data
     * 
     * @return mixed
     */
    public function create(array $data)
    {
        return $this->model->create($data);
    }

    /**
     * @param array $data
     * @param string $id
     * @param string $attribute
     * 
     * @return mixed
     */
    public function update(array $data, $id, $attribute = "id")
    {
        return $this->model->where($attribute, "=", $id)->update($data);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeModel()
    {
        $model = $this->app->make($this->model());

        return $this->model = $model->newQuery();
    }
}