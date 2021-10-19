<?php
require_once "functions.php";

clearMen();

# i [Escreva uma função que receba um array de valores numéricos e retorne o valor mais alto.]
$maior = maiorValor([1, 4, 2, 3, 5, 0]);
print "(i) Maior valor: " . $maior . "\n";
print("-----------------------------------------------------\n");

# ii [Escreva uma função que receba um array de valores numéricos e retorne a soma dos valores.]
$somaDosValores = somarValores([5, 1, 70, 99, 30, 56, 19]);
print "(ii) Soma valores: " . $somaDosValores . "\n";
print("-----------------------------------------------------\n");

# iii [Escreva uma função que receba dois arrays e retorne um array de valores intercalados.]
$valoresIntercalados = intercalar([1, 3, 5], [7, 8, 9]);
print "(iii) Valores intercalados: " . implode(',', $valoresIntercalados) . "\n";
print("-----------------------------------------------------\n");

# iv [Escreva uma função como a anterior, mas que retorne um array de pares.]
$nomes = ["Luiz", "Neymar", "Lionel"];
$sobrenomes = ["Moura", "Jr.", "Messi"];
$valoresEmPares = formarPares($nomes, $sobrenomes);
print "(iv) Valores em pares: \n";
var_dump($valoresEmPares);
print("-----------------------------------------------------\n");

# v [Escreva uma função que embaralhe um array.]
$valoresDesordenados = desordenar([1, 2, 3, 4, 5]);
print "(v) Valores desordenados: " . implode(",", $valoresDesordenados) . "\n";
print("-----------------------------------------------------\n");

/* vi [Escreva uma função que receba um array associativo e um array de strings,
e retorne uma versão do primeiro array somente com as chaves do segundo.] */
$pessoa = ['nome' => 'Jacó', 'idade' => 74, 'profissão' => 'ancião'];
$colunas = ['nome', 'profissão'];
$consulta = consultar($pessoa, $colunas);
print "(vi) Resultado da pesquisa: ";
var_dump($consulta);
print("-----------------------------------------------------\n");

# vii [Escreva uma função que ordene um array.]
$valoresOrdenados = ordenarValores([5,1,10,18,3,17,4]);
print "(vii) Valores ordenados: " . implode(",", $valoresOrdenados) . "\n";
print("-----------------------------------------------------\n");

# viii [Escreva uma função que remova valores duplicados de um array.]
$valoresUnicos = removerValoresDuplicados([1, 2, 3, 3, 4, 5, 4]);
print "(viii) Valores unicos: " . implode(",", $valoresUnicos) . "\n";
print("-----------------------------------------------------\n");

# ix [Escreva uma função que reverta um array.]
$valoresEmOrdemInvetida = inverterOrdem([1, 2, 3, 4, 5, 6, 7, 8, 9, 10]);
print "(ix) Valores em ordem invertida: " . implode(",", $valoresEmOrdemInvetida) . "\n";
print("-----------------------------------------------------\n");

# x [Desafio: escreva uma função que achate um array multidimensional.]
$vetorAchatado = achatarArrayMultidimensional([1, [1, 2], [1, [2, 3, [5,3]], 4]]);
print "(x) Vetor achatado: " . implode(",", $vetorAchatado) . "\n";
print("-----------------------------------------------------\n");