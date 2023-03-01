<?php

namespace App\Modules\General\Person\Entity;

final class PersonContact
{
  public function __construct(
    public ?int $id = 0,
    public ?int $person_id = 0,
    public string $phone,
  ){}
}