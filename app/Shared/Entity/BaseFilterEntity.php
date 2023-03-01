<?php

namespace App\Shared\Entity;

class BaseFilterEntity
{  
  public function __construct(
    public ?int $page = 0,
    public ?int $limit = 0,
    public ?string $columns = '',
    public ?string $order_by = '',    
  ){    
  }
}
