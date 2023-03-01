<?php

namespace App\Modules\General\Person\Entity;

use App\Shared\Entity\BaseEntity;

class Person extends BaseEntity
{
  public function __construct(
    public ?int $id = 0, 
    public string $name,
    public string $cpf_cnpj,
    public string $email,
    public string $date_of_birth,
    public string $nationality,
    public ?string $created_at = '',
    public ?string $updated_at = '',

    /** @var PersonContactEntity[] */
    public ?array $person_contacts,
  ){
  }
  
  public function validate(): void
  {
    // Efetuar validação se necessário...
  }
}
