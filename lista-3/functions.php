<?php
function clearMen() {
  popen('cls || clear','w');
}

function maiorValor(array $valores) : float {
  $valoresOrdenados = array();

  foreach ($valores as $valor) {
    if (empty($valoresOrdenados)) {
      array_push($valoresOrdenados, $valor);
      continue;
    }

    $ultimaVolta = count($valoresOrdenados) - 1;
    $i = 0;

    foreach ($valoresOrdenados as $key => $valorOrdenado) {
      if ($valor > $valorOrdenado) {
        array_splice($valoresOrdenados, $key, 0, $valor);
        break;
      }

      if ($i == $ultimaVolta) {
        array_push($valoresOrdenados, $valor);
      }

      $i++;
    }
  }

  return $valoresOrdenados[0];
}

function meuMaiorValor(array $valores)
{
  if (empty($valores)) return null;
  if (count($valores) == 1) return $valores[0];

  $valor = array_pop($valores);
  $maior = meuMaiorValor($valores);

  if ($valor > $maior) return $valor;
  else                 return $maior;
}

function somarValores(array $valores) : float {
  $total = 0;
  foreach ($valores as $valor) {
    $total += $valor;
  }

  return $total;
}

function intercalar(array $valores1, array $valores2): array {
  $valoresIntercalados = [...$valores1, ...$valores2];
  return $valoresIntercalados;
}

function formarPares(array $valores1, array $valores2) : array {
  $tamanhoVetor1 = count($valores1);

  $pares = array();
  for ($i = 0; $i < $tamanhoVetor1; $i++) {
    $pares[$i] = [$valores1[$i], $valores2[$i]];
  }

  return $pares;
}

function meuDesordenar(array $valores) : array
{
  $rodadas = time() % 100;

  for ($i = 0; $i < $rodadas; $i++) {
    $valores = meuDesordenarBase($valores);
  }

  return $valores;
}

function meuDesordenarBase(array $valores) : array
{
  if (count($valores) == 1) {
    return $valores;
  }

  $valor = array_pop($valores);
  $resto = meuDesordenarBase($valores);

  if ($valor % 2 == 0) {
    return juntar($valor, $resto);
  } else {
    return juntar($resto, $valor);
  }
}

function juntar($uno, $dos)
{
  if (!is_array($uno)) {
    $uno = [$uno];
  }

  if (!is_array($dos)) {
    $dos = [$dos];
  }

  foreach ($dos as $do) {
    $uno[] = $do;
  }

  return $uno;
}

function desordenar(array $valores) : array {
  $desordenados = array();
  $utilizados = array();

  $dicionario = range(0, count($valores) - 1);

  for ($i = 0; $i < count($valores); $i++) {
    $dicionario = array_values(array_diff($dicionario, $utilizados));

    if (count($dicionario) > 1) {
      $chave = random_int($dicionario[0], maiorValor($dicionario));
    } else {
      $chave = $dicionario[0];
    }
    $desordenados[$i] = $valores[$chave];

    array_push($utilizados, $chave);
  }

  /*
  $i = 0;
  $total = count($valores) - 1;
  $utilizados = array();

  for ($i = 0; $i <= $total; $i++) {
    $aleatorio = mt_rand(0, $total);

    while (in_array($aleatorio, $utilizados)) {
      $aleatorio = mt_rand(0, $total);
    }

    $desordenados[$i] = $valores[$aleatorio];
    $utilizados[] = $aleatorio;
  }
  */

  // usort($valores, 'prioridade');
  // return $valores;

  return $desordenados;
}

function prioridade($a, $b) {
  return rand(-1, 1);
}

function consultar(array $tabela, array $campos) : array {
  $resultado = array();
  // campos = colunas (indice do vetor)
  $colunas = array_keys($tabela);

  foreach ($campos as $campo) {
    if (in_array($campo, $colunas)) {
      $resultado[$campo] = $tabela[$campo];
    }
  }

  return $resultado;
}

function ordenarValores(array $valores) : array {
  $valoresOrdenados = array();

  foreach ($valores as $valor) {
    if (empty($valoresOrdenados)) {
      array_push($valoresOrdenados, $valor);
      continue;
    }

    $ultimaVolta = count($valoresOrdenados) - 1;
    $i = 0;
    foreach ($valoresOrdenados as $key => $valorOrdenado) {
      if ($valor < $valorOrdenado) {
        array_splice($valoresOrdenados, $key, 0, $valor);
        break;
      }

      if ($i == $ultimaVolta) {
        array_push($valoresOrdenados, $valor);
      }

      $i++;
    }
  }

  return $valoresOrdenados;
}

function removerValoresDuplicados(array $valores) : array {
  $unicos = array();

  foreach ($valores as $valor) {
    if (!in_array($valor, $unicos)) {
      array_push($unicos, $valor);
    }
  }

  return $unicos;
}

function inverterOrdem(array $valores) : array {
  $ordemInvertida = array();

  $total = count($valores) - 1;
  for ($i = $total; $i >= 0; $i--) {
    array_push($ordemInvertida, $valores[$i]);
  }

  return $ordemInvertida;
}

function meuInverterOrdem(array $valores) : array {
  $invertido = [];
  while (!empty($valores)) $invertido[] = array_pop($valores);
  return $invertido;
}

function achatarArrayMultidimensional(array $valores) : array {
  $simples = array();

  while ($item = array_shift($valores)) {
    if (is_array($item)) {
      array_push($simples, ...achatarArrayMultidimensional($item));
    } else {
      array_push($simples, $item);
    }
  }

  return $simples;
}