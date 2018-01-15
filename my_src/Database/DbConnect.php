<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace App\Database;

/**
 * Description of DbConnect
 *
 * @author anan
 */
class DbConnect 
{
  // specify your own database credentials
  private $host;
  private $db_name;
  private $username ;
  private $password;
  public $conn;

  public function __construct($dbArgs) {

      $this->host = $dbArgs["host"];
      $this->db_name = $dbArgs["database_name"];
      $this->username = $dbArgs["username"];
      $this->password = $dbArgs["password"];
  }
  // get the database connection
  public function getConnection(){

      $this->conn = null;

      try{
          $this->conn = new \PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
          $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
          $this->conn->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
          $this->conn->exec("set names utf8");
      }catch(PDOException $exception){
          echo "Connection error: " . $exception->getMessage();
      }

      return $this->conn;
  }

  public function close() {
      $this->conn = null;
  }
  public function __destruct() {
      $this->close();
  }
}
