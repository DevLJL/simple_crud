<?php

namespace App\Exceptions;

use App\Shared\Util\Response\Res;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Response;
use Throwable;

class Handler extends ExceptionHandler
{
  /**
   * A list of exception types with their corresponding custom log levels.
   *
   * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
   */
  protected $levels = [
      //
  ];

  /**
   * A list of the exception types that are not reported.
   *
   * @var array<int, class-string<\Throwable>>
   */
  protected $dontReport = [
      //
  ];

  /**
   * A list of the inputs that are never flashed to the session on validation exceptions.
   *
   * @var array<int, string>
   */
  protected $dontFlash = [
      'current_password',
      'password',
      'password_confirmation',
  ];

  /**
   * Render an exception into an HTTP response.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Throwable  $exception
   * @return \Symfony\Component\HttpFoundation\Response
   *
   * @throws \Throwable
   */
  public function render($request, Throwable $exception)
  {
    $exceptionName = (new \ReflectionClass($exception))->getShortName();

    // Rota não encontrada
    if ($exceptionName === 'NotFoundHttpException') {
      return Res::error(
        msg: trans('message.not_found_route_http'),
        code: Response::HTTP_NOT_FOUND,
      );
    }

    // Rota não encontrada
    if ($exceptionName === 'RouteNotFoundException') {
      return Res::error(
        msg: trans('message.route_not_found_or_token_invalid'),
        code: Response::HTTP_NOT_FOUND,
      );
    }

    // Model não encontrado
    if ($exceptionName === 'ModelNotFoundException') {
      return Res::error(
        code: $exception->status(),
        msg: $exception->errors(),
      );
    }
    
    // Validação dos dados
    if ($exceptionName === 'ValidationException') {
      return Res::error(
        data: $exception->errors(),
        code: $exception->status,
      );
    }

    // Erro de query
    if ($exceptionName === 'QueryException') {
      return Res::error(
        msg: $exception->getMessage(),
        code: Response::HTTP_BAD_REQUEST,
      );
    }

    // Caso nenhuma exceção seja executada acima.
    return Res::error(
      msg: $exception->getMessage(),
      code: Response::HTTP_BAD_REQUEST,
    );
  }    
}
