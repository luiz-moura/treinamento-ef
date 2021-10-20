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
   * @return string|null
   */
  public function create(object|array $p) : string|null
  {
    $p = (object) $p;

    $this->model->setProduto($p);
    $id = $this->model->insert();

    return $id;
  }

  /**
   * Busca produtos
   *
   * @param array|string  $campos
   * @param string|null   $where
   * @param int|null      $limit
   *
   * @return array|null
   */
  public function read(
    array|string $campos,
    string|null $where,
    int|null $limit = null
  ) : array|null
  {
    if ($limit == 1) {
      return $this->model->first($campos, $where);
    }
    return $this->model->find($campos, $where, $limit);
  }
}