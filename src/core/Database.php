<?php

namespace edustef\mvcFrame;

class Database
{
  private $cliente;

  public function __construct(array $config)
  {
    $username = $config['username'];
    $password = $config['password'];
    $dbname = $config['dbname'];

    $this->cliente = (new \MongoDB\Client(
      "mongodb+srv://$username:$password@cluster0.08zog.mongodb.net/$dbname?retryWrites=true&w=majority"
    ))->{$dbname};
  }
  public function getDB()
  {
    return $this->cliente;
  }
}
