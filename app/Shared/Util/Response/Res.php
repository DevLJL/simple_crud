<?php

namespace App\Shared\Util\Response;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;

// HTTP_OK = (200) Requisição foi bem sucedida com retorno no corpo da mensagem.
// HTTP_CREATED = (201) Requisição foi bem sucedida e um novo recurso foi criado e retornado no corpo da mensagem.
// HTTP_NO_CONTENT = (204) Requisição foi bem sucedida e não tem corpo de mensagem.
// HTTP_BAD_REQUEST = (400) Servidor não pode processar a requisição devido a alguma falha por parte do servidor. Ex: erro de sintaxe.
// HTTP_NOT_FOUND = (404) Servidor não encontrou o recurso solicitado.

class Res
{
  public static function success(mixed $data = [], int $code = Response::HTTP_OK, string $msg = ''): JsonResponse
  {
    // Quando nenhuma mensagem informado, seta um default
    if (!$msg){
      $msg = match ($code) {
        Response::HTTP_OK => trans('message.http_ok'),
        Response::HTTP_CREATED => trans('message.http_created'),
        Response::HTTP_BAD_REQUEST => trans('message.http_bad_request'),
        Response::HTTP_NOT_FOUND => trans('message.http_not_found'),
        default => '',
      };
    }

    // Retornar Resposta
    $baseResponse = new BaseResponse($code, false, $msg, $data);
    return response()->json($baseResponse, $code);        
  }

  public static function error(mixed $data = [], int $code = Response::HTTP_BAD_REQUEST, string $msg = ''): JsonResponse
  {
    // Quando nenhuma mensagem informado, seta um default
    if (!$msg) {
      $msg = match ($code) {
        Response::HTTP_BAD_REQUEST => trans('message.http_bad_request'),
        Response::HTTP_NOT_FOUND => trans('message.http_not_found'),
        default => '',
      };
    }

    // Retornar Resposta
    $baseResponse = new BaseResponse($code, true, $msg, $data);
    return response()->json($baseResponse, $code);
  }
}