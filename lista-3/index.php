<?php
require_once "functions.php";

# i
$maior = maiorValor([1, 4, 2, 3, 5, 0]);
print "(i) Maior valor: " . $maior . "\n";
print("-----------------------------------------------------\n");

# ii
$somaDosValores = somarValores([5, 1, 70, 99, 30, 56, 19]);
print "(ii) Soma valores: " . $somaDosValores . "\n";
print("-----------------------------------------------------\n");

# iii
$valoresIntercalados = intercalar([1, 3, 5], [7, 8, 9]);
print "(iii) Valores intercalados: " . implode(',', $valoresIntercalados) . "\n";
print("-----------------------------------------------------\n");

# iv
$nomes = ["Luiz", "Neymar", "Lionel"];
$sobrenomes = ["Moura", "Jr.", "Messi"];
$valoresEmPares = formarPares($nomes, $sobrenomes);
print "(iv) Valores em pares: \n";
var_dump($valoresEmPares);
print("-----------------------------------------------------\n");

# v
$valoresDesordenados = desordenar([1, 2, 3, 4, 5]);
print "(v) Valores desordenados: " . implode(",", $valoresDesordenados) . "\n";
print("-----------------------------------------------------\n");

# vi
$pessoa = ['nome' => 'Jacó', 'idade' => 74, 'profissão' => 'ancião'];
$colunas = ['nome', 'profissão'];
$consulta = consultar($pessoa, $colunas);
print "(vi) Resultado da pesquisa: ";
var_dump($consulta);
print("-----------------------------------------------------\n");

# vii
$valoresOrdenados = ordenarValores([5,1,10,18,3,17,4]);
print "(vii) Valores ordenados: " . implode(",", $valoresOrdenados) . "\n";
print("-----------------------------------------------------\n");

# viii
$valoresUnicos = removerValoresDuplicados([1, 2, 3, 3, 4, 5, 4]);
print "(viii) Valores unicos: " . implode(",", $valoresUnicos) . "\n";
print("-----------------------------------------------------\n");

# ix
$valoresEmOrdemInvetida = inverterOrdem([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
print "(ix) Valores em ordem invertida: " . implode(",", $valoresEmOrdemInvetida) . "\n";
print("-----------------------------------------------------\n");

# x
$vetorAchatado = achatarArrayMultidimensional([1, [1, 2], [1, [2, 3], 4]]);
print "(x) Vetor achatado: " . implode(",", $vetorAchatado) . "\n";
print("-----------------------------------------------------\n");