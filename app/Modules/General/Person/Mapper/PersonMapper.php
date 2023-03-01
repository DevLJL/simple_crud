<?php

namespace App\Modules\General\Person\Mapper;

use App\Modules\General\Person\Dto\PersonDto;
use App\Modules\General\Person\Entity\Person;
use App\Modules\General\Person\Entity\PersonContact;

class PersonMapper
{
  public static function mapDtoToEntity(PersonDto $personDto): Person 
  {
    // Person
    $person = new Person(...$personDto->toArray());
    
    // PersonContact
    $person->person_contacts = [];
    foreach ($personDto->person_contacts ?? [] as $personContactDto) {
      $person->person_contacts[] = new PersonContact(...$personContactDto->toArray());      
    }

    return $person;
  }  

  public static function mapArrayToEntity(array $data): Person
  {
    // Person
    $person = new Person(...$data);
    
    // PersonContact
    $person->person_contacts = [];
    foreach ($data['person_contacts'] ?? [] as $personContact) {
      $person->person_contacts[] = new PersonContact(...$personContact);      
    }

    return $person;
  }

  public static function mapEntityToDto(Person $personEntity): PersonDto 
  {
    return PersonDto::from(objectToArray($personEntity));
  }
}