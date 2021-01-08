<?php

namespace api\models;

use edustef\mvcFrame\DatabaseModel;
use JsonSerializable;

class Libro extends DatabaseModel implements JsonSerializable
{
  public string $isbn = '';
  public string $titulo = '';
  public string $subtitulo = '';
  public string $descripcion = '';
  public string $autor = '';
  public string $editorial = '';
  public string $categoria = '';
  public string $imagenPortada = '';
  public string $numEjemplaresTotales = '';
  public string $numEjemplaresDisponibles = '';

  public function attributes(): array
  {
    return [
      'isbn' => [
        'label' => 'ISBN',
        'rules' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'tableName' => self::tableName()], [self::RULE_MIN, 'min' => 13], [self::RULE_MAX, 'max' => 13]]
      ],
      'titulo' => [
        'label' => 'titulo',
        'rules' => [self::RULE_REQUIRED,  [self::RULE_MAX, 'max' => 50]]
      ],
      'subtitulo' => [
        'label' => 'subtitulo',
        'rules' => [[self::RULE_MAX, 'max' => 255]]
      ],
      'descripcion' => [
        'label' => 'descripcion',
        'rules' => []
      ],
      'autor' => [
        'label' => 'autor',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 50]]
      ],
      'editorial' => [
        'label' => 'editorial',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 50]]
      ],
      'categoria' => [
        'label' => 'categoria',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 50]]
      ],
      'imagenPortada' => [
        'label' => 'Imagen portada',
        'rules' => [self::RULE_REQUIRED, [self::RULE_MAX, 'max' => 255]]
      ],
      'numEjemplaresTotales' => [
        'label' => 'Numero ejemplares totales',
        'rules' => [self::RULE_REQUIRED, self::RULE_NUMERIC]
      ],
      'numEjemplaresDisponibles' => [
        'label' => 'Numero ejemplares disponibiles',
        'rules' => [self::RULE_REQUIRED, self::RULE_NUMERIC]
      ],
    ];
  }

  public static function tableName(): string
  {
    return 'Libro';
  }

  public function addEjemplar()
  {
    if ($this->numEjemplaresDisponibles < $this->numEjemplaresTotales) {
      $stmnt = self::prepare("UPDATE Libro SET numEjemplaresDisponibles = numEjemplaresDisponibles + 1 WHERE isbn = :isbn");
      $stmnt->bindValue(':isbn', $this->isbn);
      $stmnt->execute();

      return true;
    }

    return false;
  }

  public function removeEjemplar()
  {
    if ($this->numEjemplaresDisponibles > 0) {
      $stmnt = self::prepare("UPDATE Libro SET numEjemplaresDisponibles = numEjemplaresDisponibles - 1 WHERE isbn = :isbn");
      $stmnt->bindValue(':isbn', $this->isbn);
      $stmnt->execute();

      return true;
    }
    return false;
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
