<?php

namespace App\Repositories\Contracts;

use Illuminate\Database\Eloquent\Model;

interface ProveedorRepositoryInterface
{
    public function paginate(int $perPage = 15, array $filters = []);
    public function create(array $data): Model;
    public function update(int $id, array $data): Model;
    public function delete(int $id): bool;
    public function restore(int $id): bool;
    public function findOrFail(int $id): Model;
}