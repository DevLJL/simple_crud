<?php

namespace App\Modules\General\Person\UseCase;

use App\Modules\General\Person\Dto\PersonDto;
use App\Modules\General\Person\Mapper\PersonMapper;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;

class PersonUpdateAndShowUseCase
{
  public function __construct(
    private readonly PersonRepositoryInterface $personRepository
  ){    
  }
  
  public function execute(int $id, PersonDto $personDto): PersonDto 
  {
    $personEntity = PersonMapper::mapDtoToEntity($personDto);
    $this->personRepository->update($id, $personEntity);
    $personEntityFound = $this->personRepository->show($id);

    return PersonMapper::mapEntityToDto($personEntityFound);
  }
}
