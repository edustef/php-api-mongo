<?php

namespace edustef\mvcFrame;

abstract class DatabaseModel extends Model
{
  abstract public static function collectionName(): string;

  public function insertOne()
  {
  }

  public static function findOne(array $where)
  {
  }

  public static function find(array $where = null)
  {
    $collectionName = static::collectionName();
  }

  public static function deleteOne(array $where)
  {
    $collectionName = static::collectionName();
  }

  public function updateOne()
  {
    $collectionName = static::collectionName();
  }

  public static function prepare(string $mysql)
  {
    return Application::$app->database->pdo->prepare($mysql);
  }

  public function attributesToSave(): array
  {
    $attributes = array_filter($this->attributes(), function ($attr) {
      if (!isset($attr['toSave'])) {
        return true;
      }
      return $attr['toSave'] === true;
    });

    return array_keys($attributes);
  }
}
