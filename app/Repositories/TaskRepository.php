<?php

namespace App\Repositories;

use App\Repositories\Eloquent\Repository;

class TaskRepository extends Repository {

    /**
     * Task Model class name
     *
     * @return mixed
     */
    function model()
    {
        return 'App\Task';
    }
}