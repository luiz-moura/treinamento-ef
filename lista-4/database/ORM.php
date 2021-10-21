<?php
require_once "config.php";
require_once SITE_ROOT . "helper.php";

trait ORM {
  protected object|null $conn           = null;
  protected object|null $relationships  = null;

  /**
   * Cria a conexao com a base de dados
   *
   * @throws \Exception
   * @return object
   */
  public function connection() : object
  {
    if (!empty($this->conn)) return $this->conn;

    [
      "host"      => $host,
      "port"      => $port,
      "dbname"    => $db,
      "username"  => $user,
      "password"  => $pass
    ] = $this->getConfigsDB();

    $dsn = "pgsql:host=$host;port=$port;dbname=$db;user=$user;password=$pass";

    try {
      $this->conn = new PDO($dsn);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      return $this->conn;
    } catch (PDOException $e) {
      throw new Exception("Error ao conectar com a base de dados", 1);
    }
  }

  /**
   * Busca os paramentros de sessão do POSTGRESQL em um JSON
   *
   * @return array
   */
  private function getConfigsDB() : array
  {
    $handle = file_get_contents(SITE_ROOT . "/database/config.json");
    $configs = json_decode($handle, true);

    return $configs;
  }

  /**
   * Executa comandos SQL
   *
   * @param string $sql
   *
   * @throws \Exception
   * @return void
   */
  private function exec(string $sql) : void
  {
    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  /**
   * Retorna um vetor apenas com os atributos referente a coluna da tabela
   *
   * @return array
   */
  private function getCampos() : array
  {
    $campos = array();
    foreach ($this as $chave => $item) {
      if (
        empty($item)
        || $chave == 'conn'
        || $chave == 'table'
        || $chave == 'primary_key'
        || $chave == 'foreign_key'
        || $chave == 'relationships'
      ) continue;

      array_push($campos, $chave);
    }

    return $campos;
  }

  /**
   * Insere dados nas tabelas do banco
   *
   * @throws \Excepction
   * @return string
   */
  public function insert() : string
  {
    $campos = $this->getCampos();
    $colunas = implode(",", $campos);
    $valores = implode_wrapped(":", ",", $campos);

    $sql = "INSERT INTO $this->table ($colunas) VALUES ($valores) RETURNING id";

    try {
      $stmt = $this->conn->prepare($sql);
      foreach ($campos as $c) {
        $stmt->bindParam(":$c", $this->$c);
      }
      $stmt->execute();
      // return $this->conn->lastInsertId(); // Only int id
      return $stmt->fetchColumn();
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  /**
   * Busca dados nas tabelas
   *
   * @param array|string  $campos
   * @param string|null   $where
   * @param int|null      $limit
   *
   * @throws \Exception
   * @return array|object|null
   */
  private function select(
    array|string|null $campos = null,
    string|null       $where = null,
    int|null          $limit = null,
  ) : array|object|null
  {
    $campos = is_array($campos)
      ? implode(",", $campos)
      : (is_null($campos) ? '*' : $campos);

    $sql = "SELECT $campos FROM $this->table";
    if (!is_null($this->relationships)) {
      $class = new $this->relationships->class();
      $sql .= " LEFT JOIN $class->table ON $this->table.$this->foreign_key = $class->table.$class->primary_key";
    }
    $sql .= !is_null($where) ? " WHERE $where" : "";
    $sql .= !is_null($limit) ? " LIMIT $limit" : "";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      // $stmt->setFetchMode(PDO::FETCH_ASSOC);
      $stmt->setFetchMode(PDO::FETCH_OBJ);

      $dados = $limit == 1 ? $stmt->fetch() : $stmt->fetchAll();
      $dados = !empty($dados) ? $dados : null;

      return $dados;
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  /**
   * Busca dados e retorna todos os resultados encontrados
   *
   * @param array|string  $campos
   * @param string|null   $where
   * @param int|null      $limit
   *
   * @return array|null
   */
  public function find(
    array|string  $campos,
    string|null   $where = null,
    int|null      $limit = null
  ) : array|null
  {
    return $this->select($campos, $where, $limit);
  }

  /**
   * Busca dados e retorna o primeiro resultado encontrado
   *
   * @param array|string|null   $campos
   * @param string|null         $where
   *
   * @return object|null
   */
  public function first(
    array|string|null $campos = null,
    string|null       $where = null
  ) : object|null
  {
    return $this->select($campos, $where, 1);
  }

  /**
   * Atualiza dados
   *
   * @param array|string    $dados
   * @param string|array    $id
   *
   * @throws \Exception
   * @return void
   */
  public function update(
    array|string|null $dados = null,
    string|array $id = null
  ) : void
  {
    $dados = empty($dados) ? $this->getCampos() : $dados;

    if (is_string($dados)) {
      $campos = $dados;
    } else {
      $campos = "";
      foreach ($dados as $chave => $item) {
        $campos .= "$chave='$item',";
      }
      rtrim($campos, ',');
    }

    $sql = "UPDATE $this->table SET $campos WHERE id='$id'";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  /**
   * Deleta dados
   *
   * @param int|array|null $primary_key
   *
   * @throws \Exception
   * @return void
   */
  public function delete(int|array|null $primary_key = null) : void
  {
    $sql = "DELETE FROM $this->table WHERE id";

    if (is_array($primary_key)) {
      $sql .= " IN (" . implode(",", $primary_key) . ")";
    } else {
      $sql .= " = $primary_key";
    }

    try {
      $this->conn->exec($sql);
    } catch (PDOException $e) {
      throw new Exception("Error ao executar comandos", 1);
    }
  }

  /**
   * Cria relacionamento com outra tabala
   *
   * @param string $class
   *
   * @return void
   */
  protected function BelongsTo(string $class) : void {
    $this->relationships = (object) [
      "type"  => "BelongsTo",
      "class" => $class,
    ];
  }

  /**
   * Encerra a conexao
   *
   * @return void
   */
  public function __destruct()
  {
    $this->conn = null;
  }
}
