<?php
require_once "helper.php";

trait DB {
  protected string        $host       = "localhost";
  protected string        $port       = "5432";
  protected string        $dbname     = "postgres";
  protected string        $username   = "postgres";
  protected string        $password   = "EF01*";
  protected object|null   $conn       = null;

  public function connection() : object
  {
    if (!empty($this->conn)) return $this->conn;

    $dsn = "pgsql:host=$this->host;port=$this->port;dbname=$this->dbname;user=$this->username;password=$this->password";

    try {
      $this->conn = new PDO($dsn);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->conn;
    } catch (PDOException $e) {
      throw new Exception("Error ao conectar com a base de dados", 1);
    }
  }

  private function exec(string $sql) : void
  {
    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  public function insert(object $dados) : void
  {
    $campos = [];
    foreach ($dados as $chave => $item) {
      array_push($campos, $chave);
    }

    $colunas = implode(",", $campos);
    $valores = implode_wrapped(":", ",", $campos);
    $sql = "INSERT INTO $this->table ($colunas) VALUES ($valores)";

    try {
      $stmt = $this->conn->prepare($sql);
      foreach ($campos as $campo) {
        $stmt->bindParam(":$campo", $firstname);
      }

      $this->execute($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  public function update(object $dados) : void
  {
    $campos = [];
    foreach ($dados as $chave => $item) {
      array_push($campos, $chave);
    }
    $sql = "UPDATE $this->table SET ($colunas) WHERE $dados->id";

    try {
      $stmt = $this->conn->prepare($sql);
      foreach ($campos as $campo) {
        $stmt->bindParam(":$campo", $firstname);
      }

      $this->execute($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  public function select(array $campos, string $where) : object
  {
    $campos = implode(",", $campos);
    $sql = "SELECT $campos FROM $this->table" . !empty($where) && " WHERE $where";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();

      return $stmt->setFetchMode(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  public function delete(int|array $primaryKey) : void
  {
    $sql = "DELETE FROM $this->table WHERE id";

    if (is_array($primaryKey)) {
      $sql .= " IN (" . implode(",", $primaryKey) . ")";
    } else {
      $sql .= " = $primaryKey";
    }

    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  function __destruct()
  {
    $this->conn = null;
  }
}