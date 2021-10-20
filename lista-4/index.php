<?php
require_once "config.php";

require_once SITE_ROOT . "controllers/ProdutosController.php";
require_once SITE_ROOT . "controllers/MovimentacoesController.php";

// cadastrarProduto();

$movimentacoesController = new MovimentacoesController();
$movimentacoes = $movimentacoesController->read("movimentacoes.id, produtos.codigo");

var_dump($movimentacoes);


function cadastrarProduto() {
  $produto = array();
  $produto["nome"] = readline("Digite o nome do produto: ");
  $produto["descricao"] = readline("Digite a descricao do produto: ");
  $produto["unidade_medida_saida"] = readline("Digite a un. medida de saida do produto: ");
  $produto["unidade_medida_entrada"] = readline("Digite a un. medida de entrada do produto: ");
  $produto["quantidade_entrada"] = (int) readline("Digite a quantidade de entrada do produto: ");

  $produtoController = new ProdutosController();
  $id = $produtoController->create($produto);

  $movimentarProduto = readline("Deseja dar entrada no produto? (s/n): ");

  if ($movimentarProduto == 's') {
    $produto["id_produto"] = $id;
    cadastrarMovimentacao($produto);
  }
}

function cadastrarMovimentacao(array|null $produto = null) {
  if (is_null($produto)) {
    $codigoProduto = readline("Digite o codigo do produto: ");

    $produtoController = new ProdutosController();
    $produto = $produtoController->read("*", "codigo='$codigoProduto'");

    unset($codigoProduto);
  }

  if (!empty($produto)) {
    $movimentacao = array();
    $movimentacao["id_produto"] = $produto["id_produto"];
    $movimentacao["unidade_medida_saida"] = $produto["unidade_medida_saida"];
    $movimentacao["unidade_medida_entrada"] = $produto["unidade_medida_entrada"];
    $movimentacao["quantidade_entrada"] = $produto["quantidade_entrada"];

    $movimentacao["operacao"] = readline("Digite a operação: ");
    $movimentacao["quantidade_operacao"] = (int) readline("Digite a quantidade: ");

    $movimentacoesController = new MovimentacoesController();
    $movimentacoesController->create($movimentacao);
  }
}

function posicaoEstoque() {
  $movimentacoesController = new MovimentacoesController();
  $movimentacoes = $movimentacoesController->read("*", "WHERE id_produto='612ee90e-5029-4623-add7-fc981eb7184e'");

  print("+--------------------------------------------------------------------------+");
  print("|                      RELATORIO DE POSICAO DE ESTOQUE                     |");
  print("+--------------------------------------------------------------------------+");

}