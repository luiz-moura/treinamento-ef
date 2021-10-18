<?php
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

function desordenar(array $valores) : array|null {
  $desordenados = array();

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

  return $desordenados;
}

function consultar(array $tabela, array $campos) : array {
  $resultado = array();

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

  for ($i = 0; $i <= $total; $i++) {
    $ordemInvertida[$i] = $valores[$total - $i];
  }

  return $ordemInvertida;
}

function achatarArrayMultidimensional(array $valores) : array {
  $simples = array();
  foreach ($valores as $item) {
    while ($item = array_shift($valores)) {
      if (is_array($item)) {
        if (is_array($item)) {
          array_push($simples, ...achatarArrayMultidimensional($item));
        } else {
          array_push($simples, ...$item);
        }
      } else {
        array_push($simples, $item);
      }
    }
  }

  return $simples;
}