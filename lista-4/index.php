<?php
require_once "classes/Produtos.php";
require_once "classes/Movimentacoes.php";

$produto = array();
$produto["nome"] = readline("Digite o nome do produto: ");
$produto["descricao"] = readline("Digite a descricao do produto: ");
$produto["unidade_medida_saida"] = readline("Digite a un. medida de saida do produto: ");
$produto["unidade_medida_entrada"] = readline("Digite a un. medida de entrada do produto: ");
$produto["quantidade_entrada"] = (int) readline("Digite a quantidade de entrada do produto: ");

$produto = new Produtos(...$produto);

$movimentacao = array();
$movimentacao["operacao"] = readline("Digite a operação: ");
$movimentacao["quantidade"] = readline("Digite a quantidade: ");
$movimentacao["custo_unitario"] = readline("Digite o custo unitario: ");
$movimentacao["unidade_medida_saida"] = readline("Digite a un. medida saida: ");
$movimentacao["unidade_medida_entrada"] = readline("Digite a un. medida entrada: ");
$movimentacao["quantidade_entrada"] = readline("Digite a quantidade de entrada: ");

$movimentacao = new Movimentacao(...$movimentacao);
