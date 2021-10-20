<?php
require_once "config.php";

require_once SITE_ROOT . "database\ORM.php";

class Produtos {
  use ORM;

  public    string  $table = "produtos";
  public    string  $primary_key = "id";

  private   string  $nome;
  private   string  $descricao;
  private   string  $quantidade;
  private   string  $unidade_medida_saida;
  private   string  $unidade_medida_entrada;
  private   int     $quantidade_entrada;
  private   bool    $ativo = true;

  /**
   * Construtor
   *
   * @return void
   */
  public function __construct()
  {
    $this->connection();
  }

  /**
   * Seta os dados de produto
   *
   * @param object $p
   *
   * @return void
   */
  public function setProduto(object $p) : void
  {
    $this->nome                   = $p->nome;
    $this->descricao              = $p->descricao;
    $this->quantidade             = $p->quantidade;
    $this->unidade_medida_saida   = $p->unidade_medida_saida;
    $this->unidade_medida_entrada = $p->unidade_medida_entrada;
    $this->quantidade_entrada     = $p->quantidade_entrada;
    $this->ativo                  = empty($p->ativo) ? 1 : $p->ativo;
  }
}