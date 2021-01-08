<?php

namespace api\models;

use edustef\mvcFrame\DatabaseModel;
use JsonSerializable;

class Cliente extends DatabaseModel implements JsonSerializable
{
  public string $dni = '';
  public string $nombre = '';
  public string $apellidos = '';
  public string $edad = '';
  public string $direccion = '';
  public string $poblacion = '';
  public string $telefono = '';
  public string $email = '';

  public function attributes(): array
  {
    return [
      'dni' => [
        'label' => 'DNI',
        'rules' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'tableName' => self::tableName()], [self::RULE_MIN, 'min' => 10], [self::RULE_MAX, 'max' => 10]]
      ],
      'nombre' => [
        'label' => 'Nombre',
        'rules' => [self::RULE_REQUIRED,  [self::RULE_MAX, 'max' => 50]]
      ],
      'apellidos' => [
        'label' => 'Apellidos',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 50]]
      ],
      'edad' => [
        'label' => 'Edad',
        'rules' => [self::RULE_REQUIRED, self::RULE_NUMERIC]
      ],
      'poblacion' => [
        'label' => 'poblacion',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 60]]
      ],
      'direccion' => [
        'label' => 'Direccion',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 255]]
      ],
      'telefono' => [
        'label' => 'Telefono',
        'rules' => [self::RULE_NUMERIC, [self::RULE_MIN, 'min' => 8], [self::RULE_MAX, 'max' => 10]]
      ],
      'email' => [
        'label' => 'Email',
        'rules' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_MAX, 'max' => 60]]
      ]
    ];
  }

  public static function tableName(): string
  {
    return 'Cliente';
  }

  public static function getNames()
  {
    $tableName = self::tableName();
    $stmnt = self::prepare("SELECT nombre FROM " . $tableName);
    $stmnt->execute();
    return $stmnt->fetchAll(\PDO::FETCH_ASSOC);
  }

  public function jsonSerialize()
  {
    $jsonData = [];
    foreach ($this->attributes() as $attribute => $attributeData) {
      $jsonData[$attribute] = $this->{$attribute};
    }

    return $jsonData;
  }
}
