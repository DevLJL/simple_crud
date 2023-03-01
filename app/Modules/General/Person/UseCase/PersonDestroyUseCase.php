<?php

namespace App\Modules\General\Person\UseCase;

use App\Modules\General\Person\Repository\PersonRepositoryInterface;

class PersonDestroyUseCase
{
  public function __construct(
    private readonly PersonRepositoryInterface $personRepository
  ){    
  }
  
  public function execute(int $id): bool
  {
    return $this->personRepository->destroy($id);
  }
}
