<?php
require_once "config.php";
require_once SITE_ROOT . "helper.php";

require_once SITE_ROOT . "controllers/ProdutosController.php";
require_once SITE_ROOT . "controllers/MovimentacoesController.php";

$produtoController = new ProdutosController();
$movimentacoesController = new MovimentacoesController();

app();

function app() {
  global $produtoController;
  global $movimentacoesController;

  $opcao = opcoes();
  clearCLI();

  switch ($opcao) {
    case CADASTRAR_PRODUTO:
      cadastrarProduto();
      break;
    case VISUALIZAR_PRODUTOS:
      print $produtoController->produtosToString();
      break;
    case REGISTRAR_MOVIMENTACAO:
      registrarMovimentacao();
      break;
    case POSICAO_ESTOQUE:
      ralatorioPosicaoEstoque();
      break;
    case MOVIMENTACOES:
      ralatorioMovimentacoes();
      break;
    case SAIR:
      exit;
    default:
      app();
  }
  passthru("pause");
  app();
}

function opcoes() {
  clearCLI();
  print "+--------------------------------------------------------------------------+\n";
  print "|                                  OPCOES                                  |\n";
  print "+-----+--------------------------------------------------------------------+\n";
  print mb_str_pad("| [1] | Cadastrar produto", 75) . "|\n";
  print mb_str_pad("| [2] | Visualizar produtos", 75) . "|\n";
  print "+-----+--------------------------------------------------------------------+\n";
  print mb_str_pad("| [3] | Registrar movimentacao de produto", 75) . "|\n";
  print "+-----+--------------------------------------------------------------------+\n";
  print mb_str_pad("| [4] | Gerar relatório de posicao de estoque", 75) . "|\n";
  print mb_str_pad("| [5] | Gerar relatório de movimentacao de estoque", 75) . "|\n";
  print "+-----+--------------------------------------------------------------------+\n";
  print mb_str_pad("| [6] | SAIR", 75) . "|\n";
  print "+-----+--------------------------------------------------------------------+\n";

  return readline("Digite uma opcao: ");
}

function cadastrarProduto() {
  global $produtoController;

  $produto = array();
  $produto["nome"] = readline("Digite o nome do produto: ");
  $produto["descricao"] = readline("Digite a descricao do produto: ");
  $produto["unidade_medida_saida"] = readline("Digite a un. medida de saida do produto (UN/CX): ");
  $produto["unidade_medida_entrada"] = readline("Digite a un. medida de entrada do produto (UN/CX): ");
  $produto["quantidade_entrada"] = readline("Digite a quantidade de entrada do produto: ");

  try {
    $produto["id"] = $produtoController->create($produto);
    print "Produto criado com sucesso!\n";
  } catch (Exception $e) {
    print $e->getMessage() . "\n";
    return;
  }

  $movimentar = readline("Deseja dar entrada no produto? (s/n): ");

  if (strtolower($movimentar) == "s") {
    registrarMovimentacao((object) $produto, "E");
  }
}

function registrarMovimentacao(object|null $produto = null, string|null $operacao = null) {
  global $movimentacoesController;
  global $produtoController;

  if (is_null($produto)) {
    $codigoProduto = readline("Digite o codigo do produto: ");
    if (!isInteger($codigoProduto)) {
      print "Você não digitou um numero valido\n";
      return;
    }

    $produto = $produtoController->read("*", "codigo='$codigoProduto'", 1);
    if (is_null($produto)) {
      print "Produto não encontrado\n";
      return;
    }
  }

  $movimentacao = array();
  $movimentacao["id_produto"] = $produto->id;
  $movimentacao["unidade_medida_saida"] = $produto->unidade_medida_saida;
  $movimentacao["unidade_medida_entrada"] = $produto->unidade_medida_entrada;
  $movimentacao["quantidade_entrada"] = $produto->quantidade_entrada;
  $movimentacao["quantidade_operacao"] = readline("Digite a quantidade: ");
  $movimentacao["data_operacao"] = readline("Digite a data em que a operacao ocorreu: ");
  $movimentacao["operacao"] = ($operacao ?: readline("Digite a operacao (E/S): "));

  try {
    $movimentacoesController->create($movimentacao);
    print "Movimentacao criada com sucesso!\n";
  } catch (Exception $e) {
    print $e->getMessage() . "\n";
  }
}

function ralatorioPosicaoEstoque() {
  global $produtoController;

  $codigo = readline("Digite o codigo para um produto especifico (apenas ENTER pra todos): ");
  $codigo = empty($codigo) ? null: $codigo;
  if (!is_null($codigo) && !isInteger($codigo)) {
    print "Você não digitou um numero valido\n";
    return;
  }

  $relatorio = $produtoController->posicaoEstoqueToString($codigo);

  print $relatorio ?: "Nenhum dado encontrado\n";

  $salvar = readline("Deseja salvar o relatorio? (s/n): ");
}

function ralatorioMovimentacoes() {
  global $movimentacoesController;

  $codigo = readline("Digite o codigo para um produto especifico (apenas ENTER pra todos): ");
  $codigo = empty($codigo) ? null: $codigo;
  if (!is_null($codigo) && !isInteger($codigo)) {
    print "Você não digitou um numero valido\n";
    return;
  }

  $intervalo = null;
  $data = readline("Deseja consultar por intervalo de data (s/n): ");
  if (strtolower($data) == "s") {
    $dataInicio = readline("Digite a data do começo do intervalo: ");
    $dataFim = readline("Digite a data do final do intervalo: ");
    $intervalo = [$dataInicio, $dataFim];
  }

  try {
    $relatorio = $movimentacoesController->movimentacoesToString($codigo, $intervalo);
    print $relatorio ?: "Nenhum dado encontrado\n";

    $salvar = readline("Deseja salvar o relatorio? (s/n): ");
  } catch (Expection $e) {
    print $e->getMessage() . "\n";
  }
}
