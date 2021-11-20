<?php



class Database
{
  private $conn;
  private $dsn = 'mysql:host=localhost;dbname=ecommerce';
  private $username = 'root';
  private $password = '';

  public function connect()
  {
    $this->conn = null;
    try {
      $this->conn = new \PDO($this->dsn, $this->username, $this->password);
      $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
    return $this->conn;
  }
}
