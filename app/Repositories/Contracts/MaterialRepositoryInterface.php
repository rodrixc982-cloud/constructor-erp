<?php

namespace App\Repositories\Contracts;

interface MaterialRepositoryInterface extends RepositoryInterface
{
    public function siguienteCodigo(): string;
}
