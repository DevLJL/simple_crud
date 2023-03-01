<?php

namespace App\Shared\Util\Response;

class BaseResponse 
{
  public function __construct(
    public int $code,
    public bool $error,
    public string $message,
    public mixed $data,
  ){
  }
}
