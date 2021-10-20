<?php
require_once "config.php";

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
   * @return string|null
   */
  public function create(object|array $m) : string|null
  {
    $m = (object) $m;

    $this->model->setMovimentacao($m);
    $id = $this->model->insert();

    return $id;
  }

  /**
   * Busca movimentacoes
   *
   * @param array|string  $campos
   * @param string|null   $where
   * @param int|null      $limit
   *
   * @return array|null
   */
  public function read(
    array|string  $campos,
    string|null   $where,
    int|null      $limit = null
  ) : array|null
  {
    if ($limit == 1) {
      return $this->model->first($campos, $where);
    }
    return $this->model->find($campos, $where, $limit);
  }
}