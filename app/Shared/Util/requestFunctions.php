<?php
// Como configurar arquivo helper? 
// Adicione o caminho em composer.json na seção autoload, files. 
// Depois rodar comando composer dump-autoload -o

/**
 * Obter parâmetro da rota atual
 *
 * @param [type] $route
 * @return void
 */
function getRouteParameter($route = null)
{
  if (!$route) 
    $route = request()->route();
  
  $parameters = $route->parameters ?? [];
  return array_shift($parameters);
}
