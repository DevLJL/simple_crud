<?php

namespace App\Modules\General\Person\Repository\Eloquent\Model;

use App\Shared\Repository\Eloquent\Model\BaseModelEloquent;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PersonModelEloquent extends BaseModelEloquent
{
  use HasFactory;

  protected $table = 'persons';
  protected $fillable = [
    'name',
    'cpf_cnpj',
    'email',
    'date_of_birth',
    'nationality',
  ];
  
  protected $casts = [
  ];

  public function personContacts()
  {
    return $this->hasMany(PersonContactModelEloquent::class, 'person_id', 'id');
  }
}