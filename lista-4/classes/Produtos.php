<?php
require_once "DB.php";

class Produtos {
  use DB;

  public function __construct(
    private   string  $nome,
    private   string  $descricao,
    private   string  $unidade_medida_saida,
    private   string  $unidade_medida_entrada,
    private   int     $quantidade_entrada,
    private   float   $ultimo_custo_unitario = 0,
    private   bool    $ativo = true,
    protected string  $table = "produtos",
  )
  {
    $this->connection();
  }
}