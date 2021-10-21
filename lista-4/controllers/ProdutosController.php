<?php
require_once "config.php";

require_once SITE_ROOT . "models\Produtos.php";

class ProdutosController {
  private object $model;

  /**
   * Construtor
   *
   * @return void
   */
  public function __construct()
  {
    $this->model = new Produtos();
  }

  /**
   * Cria produto
   *
   * @param object|array $p
   *
   * @throws \Exception
   * @return string
   */
  public function create(object|array $p) : string
  {
    $p = (object) $p;

    # Verifica o tipo
    if (
      !is_string($p->nome)
      || !is_string($p->descricao)
      || !isInteger($p->quantidade_entrada)
      || !is_string($p->unidade_medida_saida)
      || !is_string($p->unidade_medida_entrada)
      || !in_array(strtoupper($p->unidade_medida_saida), ["UN", "CX"])
      || !in_array(strtoupper($p->unidade_medida_entrada), ["UN", "CX"])
    ) throw new Exception("Um ou mais dados inseridos estão incorretos", 1);

    strtoupper($p->unidade_medida_saida);
    strtoupper($p->unidade_medida_entrada);

    $this->model->setProduto($p);
    return $id = $this->model->insert();
  }

  /**
   * Busca produtos
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
    if ($limit == 1) {
      return $this->model->first($campos, $where);
    }
    return $this->model->find($campos, $where, $limit);
  }

  /**
   * Atualiza produtos
   *
   * @param array|string|null $dados
   * @param string|array      $id
   *
   * @return void
   */
  public function update(
    array|string|null $dados = null,
    string|array      $id = null
  ) : void
  {
    $this->model->update($dados, $id);
  }

  /**
   * Retorna uma string com todos os produtos
   *
   * @param int|null $codigoProduto
   *
   * @return string|null
   */
  public function produtosToString(int|null $codigoProduto = null) : string|null
  {
    $where = !empty($codigoProduto) ? "codigo='$codigoProduto'" : null;
    $produtos = $this->model->find('*', $where);

    if (empty($produtos)) return null;

    $filtro = empty($codigoProduto) ? "TODOS PRODUTOS" : "CODIGO $codigoProduto";

    $relatorio = "";
    $relatorio .= "+--------------------------------------------------------------------------+\n";
    $relatorio .= "|                           RELATORIO DE PRODUTOS                          |\n";
    $relatorio .= "+--------------------------------------------------------------------------+\n";
    // $relatorio .= mb_str_pad("| FITLRAR: $filtro", 75) ."|\n";
    // $relatorio .= "+------+--------------------------+-------------------------+--------------+\n";
    $relatorio .= "| COD. | PRODUTO                  | DESCRICAO               |    ATIVO     |\n";
    $relatorio .= "+------+--------------------------+-------------------------+--------------+\n";
    foreach ($produtos as $produto) {
      $ativo = $produto->ativo ? "ATIVO" : "INATIVO";
      $relatorio .= mb_str_pad("| $produto->codigo", 7);
      $relatorio .= mb_str_pad("| $produto->nome", 27);
      $relatorio .= mb_str_pad("| $produto->descricao", 26);
      $relatorio .= mb_str_pad("| $ativo", 15) . "|\n";
    }
    $relatorio .= "+------+--------------------------+-------------------------+--------------+\n";

    return $relatorio;
  }

  /**
   * Cria o relatorio de produtos com a quantidade
   *
   * @param int|null $codigoProduto
   *
   * @return string|null
   */
  public function posicaoEstoqueToString(int|null $codigoProduto = null) : string|null
  {
    $where = !empty($codigoProduto) ? "codigo='$codigoProduto'" : null;
    $produtos = $this->model->find('*', $where);

    if (empty($produtos)) return null;

    $filtro = empty($codigoProduto) ? "TODOS PRODUTOS" : "CODIGO $codigoProduto";

    $relatorio = "";
    $relatorio .= "+--------------------------------------------------------------------------+\n";
    $relatorio .= "|                      RELATORIO DE POSICAO DE ESTOQUE                     |\n";
    $relatorio .= "+--------------------------------------------------------------------------+\n";
    $relatorio .= mb_str_pad("| FITLRAR: $filtro", 75) ."|\n";
    $relatorio .= "+------+----------------------------------------------------+--------------+\n";
    $relatorio .= "| COD. | PRODUTO                                            |  QUANTIDADE  |\n";
    $relatorio .= "+------+----------------------------------------------------+--------------+\n";
    foreach ($produtos as $produto) {
      $relatorio .= mb_str_pad("| $produto->codigo", 7);
      $relatorio .= mb_str_pad("| $produto->nome", 53);
      $relatorio .= mb_str_pad("| $produto->quantidade", 15) . "|\n";
    }
    $relatorio .= "+------+----------------------------------------------------+--------------+\n";

    return $relatorio;
  }
}
