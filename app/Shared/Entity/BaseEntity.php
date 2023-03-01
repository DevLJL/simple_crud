<?php

namespace App\Shared\Entity;

abstract class BaseEntity 
{  
  public abstract function validate(): void;
}
