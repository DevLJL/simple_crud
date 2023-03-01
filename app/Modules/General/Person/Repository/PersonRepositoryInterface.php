<?php 

namespace App\Modules\General\Person\Repository;

use App\Modules\General\Person\Entity\PersonFilter;
use App\Shared\Repository\BaseRepositoryInterface;

interface PersonRepositoryInterface extends BaseRepositoryInterface
{  
  public function index(?PersonFilter $personFilter = null): array;
}
