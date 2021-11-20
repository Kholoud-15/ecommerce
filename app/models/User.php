<?php

class User
{
  private $conn;
  private $table = 'users';
  public $firstname;
  public $lastname;
  public $type;
  public $email;
  public $email_code;
  public $password;
  public $created_at;
  public $updated_at;

  public function __construct($db)
  {
    $this->conn = $db;
  }

  public function email_exists()
  {
    $sql = 'SELECT email FROM users WHERE email=:email';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':email' => $this->email]);
    if($stmt->rowCount() > 0)
    {
      return true;
    }
    return false;
  }

  public function save()
  {
    $sql = 'INSERT INTO users(firstname, lastname, email, password, type, email_code)VALUES(:firstname,:lastname,:email,:password,:type,:email_code)';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      ':firstname' => $this->firstname,
      ':lastname' => $this->lastname,
      ':email' => $this->email,
      ':password' => $this->password,
      ':type' => $this->type,
      ':email_code' => $this->email_code
    ]);
  }

  public function activate_email()
  {
    $sql = 'SELECT * FROM users WHERE email=:email AND email_code=:email_code AND is_active=:is_active';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([
      ':email' => $this->email,
      ':email_code' => $this->email_code,
      ':is_active' => 0
    ]);
    if($stmt->rowCount())
    {
      $sql = 'UPDATE users SET is_active=:is_active WHERE email=:email';
      $stmt = $this->conn->prepare($sql);
      $stmt->execute([
        ':email' => $this->email,
        ':is_active' => 1
      ]);
      return true;
    }
    return false;
  }

  public function is_active()
  {
    $sql = 'SELECT is_active FROM users WHERE email=:email';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':email' => $this->email]);
    $row = $stmt->fetch();
    if($row['is_active'] == '1')
    {
      return true;
    }
    return false;
  }


  public function get_password_by_email()
  {
    $sql = 'SELECT password FROM users WHERE email=:email';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':email' => $this->email]);
    $row = $stmt->fetch();
    return $row['password'];
  }


  public function get_data_by_email()
  {
    $sql = 'SELECT * FROM users WHERE email=:email';
    $stmt = $this->conn->prepare($sql);
    $stmt->execute([':email' => $this->email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return $row;
  }

  public function is_logged()
  {
    if(isset($_SESSION['id']) || isset($_COOKIE['token']))
    {
      return true;
    }
  }













}
