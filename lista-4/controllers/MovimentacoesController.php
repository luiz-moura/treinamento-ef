<?php
require_once "config.php";
require_once SITE_ROOT . "helper.php";

require_once SITE_ROOT . "models\Movimentacoes.php";

class MovimentacoesController {
  private object $model;

  /**
   * Construtor
   *
   * @return void
   */
  public function __construct()
  {
    $this->model = new Movimentacoes();
  }

  /**
   * Cria Movimentacao
   *
   * @param object|array $m
   *
   * @throws \Exception
   * @return string
   */
  public function create(object|array $m) : string
  {
    $m = (object) $m;

    # Verifica o tipo
    if (
      !is_string($m->operacao)
      || !in_array(strtoupper($m->operacao), ["E", "S"])
      || !isInteger($m->quantidade_operacao)
      || !is_string($m->data_operacao)
      || !is_string($m->id_produto)
      || !isInteger($m->quantidade_entrada)
      || !is_string($m->unidade_medida_saida)
      || !is_string($m->unidade_medida_entrada)
      || !in_array(strtoupper($m->unidade_medida_saida), ["UN", "CX"])
      || !in_array(strtoupper($m->unidade_medida_entrada), ["UN", "CX"])
    ) throw new Exception("Um ou mais dados inseridos estão incorretos", 1);

    if (!($m->data_operacao = stringToDate($m->data_operacao))) {
      throw new Exception("Data no formato incorreto. Formato correto Ex.: 13/02/199", 1);
    }

    $m->operacao                = strtoupper($m->operacao);
    $m->unidade_medida_saida    = strtoupper($m->unidade_medida_saida);
    $m->unidade_medida_entrada  = strtoupper($m->unidade_medida_entrada);

    $m->quantidade_operaca = abs($m->quantidade_operacao);
    if ($m->operacao == "S") $m->quantidade_operaca = -$m->quantidade_operaca;

    $this->model->setMovimentacao($m);

    return $id = $this->model->insert();
  }

  /**
   * Busca movimentacoes
   *
   * @param array|string  $campos
   * @param string|null   $where
   * @param int|null      $limit
   *
   * @return array|object|null
   */
  public function read(
    array|string  $campos,
    string|null   $where = null,
    int|null      $limit = null
  ) : array|object|null
  {
    return $limit == 1
      ? $this->model->first($campos, $where)
      : $this->model->find($campos, $where, $limit);
  }

  /**
   * Gera o relatório de movimentacoes
   *
   * @param int|null $codigoProduto
   * @param array|null  $intervaloDatas
   *
   * @throws \Exception
   * @return string|null
   */
  function movimentacoesToString(
    int|null    $codigoProduto = null,
    array|null  $invervaloDatas = null
  ) : string|null
  {
    $where = !empty($codigoProduto) ? "codigo='$codigoProduto'" : "";
    if (!empty($invervaloDatas)) {
      $inicioData = stringToDate($invervaloDatas[0]);
      $fimData    = stringToDate($invervaloDatas[1]);

      if (!$inicioData || !$fimData) throw new Exception("Data invalida", 1);

      $where .= !empty($codigoProduto) && !empty($invervaloDatas) ? " AND ": "";
      $where .= !empty($invervaloDatas) ? "data_operacao BETWEEN '$inicioData' AND '$fimData'" : "";
    }

    $where = (!empty($where)) ? $where : null;
    $movimentacoes = $this->model->find("movimentacoes.*, produtos.codigo, produtos.nome", $where, null, "data_operacao ASC");

    if (empty($movimentacoes)) return null;

    $filtro = empty($codigoProduto) ? "TODOS PRODUTOS" : "CODIGO $codigoProduto";
    $filtro .= !empty($invervaloDatas) ? ", ENTRE $invervaloDatas[0] - $invervaloDatas[1]" : "";
    $relatorio =  "+-------------------------------------------------------------------------------------------+\n";
    $relatorio .= "|                           RELATORIO DE MOVIMENTACAO DE PRODUTOS          " . dataHoraAtual() . " |\n";
    $relatorio .= "+-------------------------------------------------------------------------------------------+\n";
    $relatorio .= mb_str_pad("| FITLRAR: $filtro", 92) ."|\n";
    $relatorio .= "+------+----------------------------------------------------+-----+------------+------------+\n";
    $relatorio .= "| COD. | PRODUTO                                            | OP. | QUANTIDADE |   DATA     |\n";
    $relatorio .= "+------+----------------------------------------------------+-----+------------+------------+\n";
    foreach ($movimentacoes as $movimentacao) {
      $data = (new DateTime($movimentacao->data_operacao))->format('d/m/Y');
      $relatorio .= mb_str_pad("| $movimentacao->codigo", 7);
      $relatorio .= mb_str_pad("| $movimentacao->nome", 53);
      $relatorio .= mb_str_pad("| $movimentacao->operacao", 6);
      $relatorio .= mb_str_pad("| $movimentacao->quantidade_operacao", 13);
      $relatorio .= mb_str_pad("| $data", 13) . "|\n";
    }
    $relatorio .= "+------+----------------------------------------------------+-----+------------+------------+\n";

    return $relatorio;
  }
}
