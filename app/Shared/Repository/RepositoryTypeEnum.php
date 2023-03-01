<?php 

namespace App\Shared\Repository;

use App\Shared\Trait\EnumTrait;

enum RepositoryTypeEnum: string
{
  use EnumTrait;
  
  case NONE = '';
  case ELOQUENT = 'eloquent';
  case MEMORY = 'memory';
  case OTHER = 'other';  
}