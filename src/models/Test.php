<?php

use edustef\mvcFrame\DatabaseModel;

class Test extends DatabaseModel
{
  public static function collectionName(): string
  {
    return 'test';
  }

  public function attributes(): array
  {
    return [];
  }
}
