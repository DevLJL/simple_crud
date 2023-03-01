<?php

namespace App\Modules\General\Person\UseCase;

use App\Modules\General\Person\Dto\PersonFilterDto;
use App\Modules\General\Person\Entity\PersonFilter;
use App\Modules\General\Person\Repository\PersonRepositoryInterface;

class PersonIndexUseCase
{
  public function __construct(
    private readonly PersonRepositoryInterface $personRepository
  ){    
  }
  
  public function execute(PersonFilterDto $personFilterDto): array
  {
    $personFilter = new PersonFilter(...$personFilterDto->toArray());
    return $this->personRepository->index($personFilter);
  }
}
