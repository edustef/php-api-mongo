<?php

namespace edustef\mvcFrame;

abstract class DatabaseModel extends Model
{
  abstract public static function tableName(): string;

  public function save()
  {
    $tableName = static::tableName();
    $attributes = array_keys($this->attributes());

    $params = array_map(fn ($attr) => ":$attr",  $attributes);

    $stmnt = self::prepare("INSERT INTO $tableName (" . implode(',', $attributes) . ") VALUES (" . implode(',', $params) . ")");
    foreach ($attributes as $attribute) {
      $stmnt->bindValue(':' . $attribute, $this->{$attribute});
    }

    $stmnt->execute();
    return true;
  }

  public static function findOne(array $where)
  {
    $tableName = static::tableName();
    $whereQuery = implode(' AND ', array_map(fn ($attr) => "$attr = :$attr", array_keys($where)));

    $stmnt = self::prepare("SELECT * FROM $tableName WHERE $whereQuery");
    foreach ($where as $key => $value) {
      $stmnt->bindValue(":$key", $value);
    }
    $stmnt->execute();
    return $stmnt->fetchObject(static::class);
  }

  public static function findAll(array $where = null)
  {
    $tableName = static::tableName();

    if ($where) {
      $whereQuery = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($where)));
      $stmnt = self::prepare("SELECT * FROM $tableName WHERE " . $whereQuery);
      foreach ($where as $key => $value) {
        $stmnt->bindValue(":$key", $value);
      }
    } else {
      $stmnt = self::prepare("SELECT * FROM " . $tableName);
    }

    $stmnt->execute();

    return $stmnt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
  }

  public static function search(array $searchAttributes, string $value)
  {
    $sql = "SET @value = :value";

    $stmnt = self::prepare($sql);
    $stmnt->bindValue(":value", "%$value%", \PDO::PARAM_STR);
    $stmnt->execute();
    $tableName = static::tableName();
    $searchAttributesGlued = implode(' OR ', array_map(fn ($attr) => "$attr LIKE @value", $searchAttributes));
    $stmnt = self::prepare("SELECT * FROM $tableName
      WHERE $searchAttributesGlued");

    $stmnt->execute();

    return $stmnt->fetchAll(\PDO::FETCH_CLASS | \PDO::FETCH_PROPS_LATE, static::class);
  }


  public static function delete(array $where): bool
  {
    $tableName = static::tableName();
    $whereQuery = implode(' AND ', array_map(fn ($key) => "$key = :$key", array_keys($where)));

    $stmnt = self::prepare("DELETE FROM $tableName WHERE $whereQuery");
    foreach ($where as $key => $value) {
      $stmnt->bindValue(":$key", $value);
    }

    $stmnt->execute();

    return $stmnt->rowCount() >= 1;
  }

  public function update(array $attributes, array $where): bool
  {
    $tableName = static::tableName();

    $params = array_map(fn ($attr) => "$attr=:$attr",  array_keys($attributes));

    $whereQuery = implode(' AND ', array_map(fn ($key) => "$key = :where$key", array_keys($where)));
    $stmnt = self::prepare("UPDATE $tableName SET " . implode(',', $params) . " WHERE $whereQuery");

    foreach ($where as $key => $value) {
      $stmnt->bindValue(":where$key", $value);
    }

    foreach ($attributes as $attribute => $attributeValue) {
      $stmnt->bindValue(":$attribute", $attributeValue);
    }
    $stmnt->execute();
    return true;
  }

  public static function prepare(string $mysql)
  {
    return Application::$app->database->pdo->prepare($mysql);
  }

  public function attributes(): array
  {
    $attributes = array_filter(parent::attributes(), function ($attr) {
      if (!isset($attr['isSaved'])) {
        return true;
      }
      return $attr['isSaved'] === true;
    });

    return array_keys($attributes);
  }
}
