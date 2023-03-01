<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

use Carbon\Carbon;
use Illuminate\Support\Str;

/**
 * Extrair apenas números de uma string
 *
 * @param string $value
 * @return string
 */
function onlyNumbers(?string $value): string
{
  if (!$value) return '';
  return preg_replace('/[^0-9]/', '', $value); 
}

/**
 * Formatar Data
 *
 * @param string $value
 * @param string $format
 * @param boolean $startOfDay
 * @param boolean $endOfDay
 * @return string
 */
function formatDate(string $value, string $format = 'Y-m-d H:i:s', bool $startOfDay = false, bool $endOfDay = false): string
{
  $result = Carbon::parse($value);

  // Horário de início de dia
  if ($startOfDay) {
    $result = $result->startOfDay();
  }
  // Horário de final de dia
  if ($endOfDay) {
    $result = $result->endOfDay();
  }
  // Formatar
  $result = $result->format($format);
  return $result;
}

/**
 * Validar CPF
 *
 * @param string $value
 * @return boolean
 */
function cpfIsValid(string $value): bool
{
  $c = preg_replace('/\D/', '', $value);
  if (strlen($c) != 11 || preg_match("/^{$c[0]}{11}$/", $c)) {
    return false;
  }

  for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
  if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
    return false;
  }

  for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
  if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
    return false;
  }

  return true;
}

/**
 * Validar CNPJ
 *
 * @param string $value
 * @return boolean
 */
function cnpjIsValid(string $value): bool
{
  $c = preg_replace('/\D/', '', $value);
  $b = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

  if (strlen($c) != 14) {
      return false;
  } elseif (preg_match("/^{$c[0]}{14}$/", $c) > 0) {
    return false;
  }

  for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);
  if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
    return false;
  }

  for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);
  if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) {
    return false;
  }

  return true;
}

/**
 * Validar CPF ou CNPJ
 *
 * @param string $value
 * @return boolean
 */
function cpfOrCnpjIsValid(string $value): bool
{
  return strlen(onlyNumbers($value)) === 11
    ? cpfIsValid($value)
    : cnpjIsValid($value);
}

/**
 * // Formatar CPF ou CNPJ
 *
 * @param string $value
 * @return string
 */
function formatCpfCnpj(string $value): string
{
  return strlen(onlyNumbers($value)) === 11
    ? preg_replace("/(\d{3})(\d{3})(\d{3})(\d{2})/", "\$1.\$2.\$3-\$4", $value)
    : preg_replace("/(\d{2})(\d{3})(\d{3})(\d{4})(\d{2})/", "\$1.\$2.\$3/\$4-\$5", $value);
}

/**
 * Remover chaves do array
 *
 * @param array $array
 * @param array $keys
 * @return array
 */
function array_except(array $array, array $keys): array
{
  foreach ($keys as $key) {
    unset($array[$key]);
  }
  return $array;
}

/**
 * Calcular e Validar GTIN
 *
 * @param string $ean13
 * @return string
 */
function calculateAndCheckEan13(string $ean13): string
{
  $ean13 = substr(onlyNumbers($ean13), 0, 12);
  // Retornar vazio (Inválido)
  if (strlen($ean13) < 12)
    return '';

  // Retornar código informado quando valor >= 14 (DUN14 ou outros)
  if (strlen($ean13) >= 14)
    return $ean13;

  // Obter resultado de cálculo entre os números (12)
  $resultCalc = 0;
  for ($i=0; $i < strlen($ean13); $i++) { 
    $resultCalc += ($i % 2 == 0) ? $ean13[$i] : ($ean13[$i] * 3);
  }

  // Retornar valor válido + Dígito Verificador
  for ($i=0; $i <= 9; $i++) { 
    if (($resultCalc + $i) % 10 == 0) 
      return $ean13 . $i;        
  }

  // Inválido
  return '';
}

/**
 * Verificar se números são iguais
 *
 * @param float $number1
 * @param float $number2
 * @param boolean $applyAbsToCompare
 * Tornar números positivos antes da comparação
 * @param float $tolerance
 * Tolerância na diferença entre os números
 * @return void
 */
function numbersAreEqual(float $number1, float $number2, bool $applyAbsToCompare = false, float $tolerance = 0)
{
  if ($applyAbsToCompare) {
    $number1 = abs($number1);
    $number2 = abs($number2);
  }
  return (($number1 - $number2) < $tolerance);
}

/**
 * Converter Objeto em Array
 *
 * @param mixed $obj
 * @return array
 */
function objectToArray(mixed $obj): array
{
  return json_decode(json_encode($obj), true);
}

/**
 * Gerar UUID
 *
 * @param boolean $ordered
 * @return string
 */
function makeUuid(bool $ordered = true): string {
 return match ($ordered) {
    true  => Str::orderedUuid()->toString(),
    false => Str::uuid()->toString(),
};          
}