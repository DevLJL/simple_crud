<?php

namespace App\Modules\General\Person\Dto;

use Spatie\LaravelData\Attributes\Validation\Rule;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\DataCollection;

class PersonDto extends Data
{
  public static function authorize(): bool
  {
    return true;
  }  

  public function __construct(
    #[Rule('nullable|integer')]
    public ?int $id,

    #[Rule('required|string|max:100')]
    public string $name,

    // Validação abaixo
    public string $cpf_cnpj,

    #[Rule('required|string|max:255|email')]
    public string $email,

    #[Rule('required|date_format:Y-m-d')]
    public string $date_of_birth,

    #[Rule('required|string|max:60')]
    public string $nationality,

    #[Rule('nullable|date_format:Y-m-d H:i:s')]
    public ?string $created_at,

    #[Rule('nullable|date_format:Y-m-d H:i:s')]
    public ?string $updated_at,

    /** @var PersonContactDto[] */
    public ?DataCollection $person_contacts,
  ){
  }

  public static function rules(): array
  {
    return [
      'cpf_cnpj' => [
        'required',
        'integer',
        fn ($att, $value, $fail) => static::rulesCpfCnpj($att, $value, $fail),
      ],      
    ];
  }

  // Validar CPF/CNPJ
  public static function rulesCpfCnpj($att, $value, $fail)
  {
    if ($value && (!cpfOrCnpjIsValid($value))) {
      $fail(trans('request_validation.field_is_not_valid', ['value' => $value]));
    }
  }
}
