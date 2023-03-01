<?php

namespace App\Shared\Dto;

class ExampleDto extends BaseFilterDto
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(){
    parent::__construct(...self::init());
  }
}
