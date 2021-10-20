<?php
require_once "config.php";

require_once SITE_ROOT . "helper.php";

trait ORM {
  protected object|null $conn = null;
  protected object|null $relationships = null;

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
      "db"        => $dbname,
      "user"      => $username,
      "pass"      => $password
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
    $handle = file_get_contents(SITE_ROOT . "/database/config_db.json");
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
   * Insere dados nas tabelas do banco
   *
   * @throws \Excepction
   * @return string
   */
  public function insert() : string
  {
    $campos = [];
    foreach ($this as $chave => $item) {
      if (
        $chave == 'table'
        || $chave == 'conn'
        || $chave == 'primary_key'
        || $chave == 'foreign_key'
        || empty($item)
      ) continue;

      array_push($campos, $chave);
    }

    $colunas = implode(",", $campos);
    $valores = implode_wrapped(":", ",", $campos);
    $sql = "INSERT INTO $this->table ($colunas) VALUES ($valores) RETURNING id";

    try {
      $stmt = $this->conn->prepare($sql);
      foreach ($campos as $c) $stmt->bindParam(":$c", $this->$c);
      if ($stmt->execute()) {
        // return $this->conn->lastInsertId(); // Only int id
        return $stmt->fetchColumn();
      }
      return null;
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
   * @return array|null
   */
  private function select(
    array|string  $campos,
    string|null   $where = null,
    int|null      $limit = null,
  ) : array|null
  {
    $campos = !is_array($campos) ? $campos : implode(",", $campos);
    $where = !is_null($where) ? "WHERE $where" : null;
    $limit = !is_null($limit) ? "LIMIT $limit" : null;

    $sql = "SELECT $campos FROM $this->table";
    if (!is_null($this->relationships)) {
      $class = new $this->relationships->class();
      $sql .= " LEFT JOIN $class->table ON $this->table.$this->foreign_key = $class->table.$class->primary_key";
    }
    $sql .= "$where $limit";

    try {
      $stmt = $this->conn->prepare($sql);
      $stmt->execute();
      $stmt->setFetchMode(PDO::FETCH_ASSOC);

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
   * @param array|string  $campos
   * @param string|null   $where
   *
   * @return array|null
   */
  public function first(array|string $campos, string|null $where) : array|null
  {
    return $this->select($campos, $where, 1);
  }

  /**
   * Atualiza dados
   *
   * @param int|array|null $id
   *
   * @throws \Exception
   * @return void
   */
  public function update(int|array|null $id = null) : void
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

  /**
   * Deleta dados
   *
   * @param int|array|null $primaryKey
   *
   * @throws \Exception
   * @return void
   */
  public function delete(int|array|null $primaryKey = null) : void
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

  /**
   * Cria relacionamento com outra tabala
   *
   * @param string $class
   *
   * @return void
   */
  protected function hasMany(string $class) : void {
    $this->relationships = (object) [
      "type"  => "hasMany",
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