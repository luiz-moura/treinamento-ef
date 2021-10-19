<?php
require_once "DB.php";

class Movimentacoes {
  use DB;

  public function __construct(
    private   int     $operacao,
    private   int     $quantidade,
    private   float   $custo_unitario,
    private   float   $unidade_medida_saida,
    private   float   $unidade_medida_entrada,
    private   int     $quantidade_entrada,
    protected string  $table = "produtos",
  )
  {
    $this->connection();
  }
}