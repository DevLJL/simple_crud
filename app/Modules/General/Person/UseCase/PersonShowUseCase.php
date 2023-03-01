<?php

namespace App\Modules\General\Person\UseCase;

use App\Modules\General\Person\Dto\PersonDto;
use App\Modules\General\Person\Mapper\PersonMapper;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;

class PersonShowUseCase
{
  public function __construct(
    private readonly PersonRepositoryInterface $personRepository
  ){    
  }
  
  public function execute(int $id): ?PersonDto
  {
    $personEntityFound = $this->personRepository->show($id);
    return $personEntityFound 
      ? PersonMapper::mapEntityToDto($personEntityFound) 
      : null;
  }
}
