<?php
require_once "config.php";

require_once SITE_ROOT . "database\ORM.php";

require_once SITE_ROOT . "models\Produtos.php";

class Movimentacoes {
  use ORM;

  public    string  $table = "movimentacoes";
  public    string  $primary_key = "id";
  public    string  $foreign_key = "id_produto";

  private   string  $id_produto;
  private   string  $operacao;
  private   int     $quantidade_operacao;
  private   string  $data_operacao;
  private   string  $unidade_medida_saida;
  private   string  $unidade_medida_entrada;
  private   int     $quantidade_entrada;

  /**
   * Construtor
   *
   * @return void
   */
  public function __construct()
  {
    $this->connection();
    $this->BelongsTo(Produtos::class);
  }

  /**
   * Seta os dados da movimentacao
   *
   * @param object $m
   *
   * @return void
   */
  public function setMovimentacao(object $m) : void
  {
    $this->id_produto             = $m->id_produto;
    $this->operacao               = $m->operacao;
    $this->quantidade_operacao    = $m->quantidade_operacao;
    $this->data_operacao          = $m->data_operacao;
    $this->unidade_medida_saida   = $m->unidade_medida_saida;
    $this->unidade_medida_entrada = $m->unidade_medida_entrada;
    $this->quantidade_entrada     = $m->quantidade_entrada;
  }
}