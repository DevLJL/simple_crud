<?php

namespace App\Modules\General\Person\Entity;

use App\Shared\Entity\BaseFilterEntity;

class PersonFilter extends BaseFilterEntity
{
  /**
   * @param integer|null $page
   * @param integer|null $limit
   * @param string|null $columns
   * @param string|null $order_by
   * @param array|null $custom_search (array of string)
   */
  public function __construct(
    public ?int $page = 0,
    public ?int $limit = 0,
    public ?string $columns = '',
    public ?string $order_by = '',    
    public ?array $custom_search = [],
  ){
    parent::__construct(
      $page, 
      $limit,
      $columns,
      $order_by
    );
  }
}
