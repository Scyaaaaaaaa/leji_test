<?php

namespace App\Models\Repositories;

use App\Exceptions\RepositoryException;

trait RepositoryTrait
{

    protected $repositoryInstance;

    public function repository()
    {
        if (!$this->repository or !class_exists($this->repository)) {
            throw new RepositoryException('Please set the $repository property to your repository path.');
        }

        if (!$this->repositoryInstance) {
            $this->repositoryInstance = app($this->repository);
            $this->repositoryInstance->setModel($this);
        }

        return $this->repositoryInstance;
    }
}
