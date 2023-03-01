<?php

namespace App\Modules\General\Person\Controller;

use App\Http\Controllers\Controller;
use App\Modules\General\Person\Dto\PersonDto;
use App\Modules\General\Person\Dto\PersonFilterDto;
use App\Modules\General\Person\UseCase\PersonDestroyUseCase;
use App\Modules\General\Person\UseCase\PersonIndexUseCase;
use App\Modules\General\Person\UseCase\PersonShowUseCase;
use App\Modules\General\Person\UseCase\PersonStoreAndShowUseCase;
use App\Modules\General\Person\UseCase\PersonUpdateAndShowUseCase;
use App\Shared\Util\Response\Res;
use Illuminate\Http\Response;

class PersonController extends Controller
{
  public function __construct(
    private readonly PersonDestroyUseCase $personDestroyUseCase,
    private readonly PersonIndexUseCase $personIndexUseCase,
    private readonly PersonShowUseCase $personShowUseCase,
    private readonly PersonStoreAndShowUseCase $personStoreAndShowUseCase,
    private readonly PersonUpdateAndShowUseCase $personUpdateAndShowUseCase,
  ){
  }
  
  public function destroy(int $id)
  {
    return $this->personDestroyUseCase->execute($id) 
      ? Res::success (code: Response::HTTP_NO_CONTENT)
      : Res::error   (code: Response::HTTP_NOT_FOUND);
  }

  public function index(PersonFilterDto $personFilterDto)
  {
    $arrayResult = $this->personIndexUseCase->execute($personFilterDto);
    return Res::success($arrayResult);
  }

  public function show(int $id)
  {
    $personDtoFound = $this->personShowUseCase->execute($id);
    return ($personDtoFound)
      ? Res::success ($personDtoFound)
      : Res::error   (code: Response::HTTP_NOT_FOUND);
  }

  public function store(PersonDto $personDto)
  {
    $personDtoStored = $this->personStoreAndShowUseCase->execute($personDto);
    return Res::success($personDtoStored, Response::HTTP_CREATED);
  }

  public function update(int $id, PersonDto $personDto)
  {
    $personDtoUpdated = $this->personUpdateAndShowUseCase->execute($id, $personDto);
    return Res::success($personDtoUpdated);
  }
}
