<?php

namespace App\Shared\Dto;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;

class BaseFilterDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $page,

    #[Rule('nullable|integer')]
    public ?int $limit,

    #[Rule('nullable|string')]
    public ?string $columns,

    #[Rule('nullable|string')]
    public ?string $order_by,    
  ){
    $default = static::init();
    if (is_null($this->page)) $this->page = $default['page'];
    if (is_null($this->limit))  $this->limit = $default['limit'];
  }

  public static function init()
  {
    return [
      'page' => request('page', 0),
      'limit' => request('limit', 0),
      'columns' => request('columns', ''),
      'order_by' => request('order_by', ''),
    ];
  }
}
