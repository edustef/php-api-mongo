<?php

namespace edustef\mvcFrame;

class Database
{
  private \MongoDB\Database $db;

  public function __construct(array $config)
  {
    $username = $config['username'];
    $password = $config['password'];
    $dbname = $config['dbname'];
    $uri = "mongodb+srv://$username:$password@cluster0.08zog.mongodb.net/$dbname?retryWrites=true&w=majority";
    $this->db = (new \MongoDB\Client(!$config['db_local'] ? $uri : 'mongodb://db:27017'))->{$dbname};
  }
  public function getDB() :\MongoDB\Database
  {
    return $this->db;
  }
}
