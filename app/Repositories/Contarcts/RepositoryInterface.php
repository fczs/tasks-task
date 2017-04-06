<?php

namespace App\Repositories\Contracts;

interface RepositoryInterface
{
    public function all();

    public function byOrder();

    public function create(array $data);

    public function update(array $data, $id);
}