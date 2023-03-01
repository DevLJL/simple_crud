<?php

namespace App\Modules\General\Person\UseCase;

use App\Modules\General\Person\Dto\PersonDto;
use App\Modules\General\Person\Mapper\PersonMapper;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;

class PersonStoreAndShowUseCase
{
  public function __construct(
    private readonly PersonRepositoryInterface $personRepository
  ){    
  }
  
  public function execute(PersonDto $personDto): PersonDto 
  {
    $personEntity = PersonMapper::mapDtoToEntity($personDto);
    $storedId = $this->personRepository->store($personEntity);
    $personEntityFound = $this->personRepository->show($storedId);
    
    return PersonMapper::mapEntityToDto($personEntityFound);
  }
}
