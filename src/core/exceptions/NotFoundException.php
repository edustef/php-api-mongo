<?php

namespace edustef\mvcFrame\exceptions;

class NotFoundException extends \Exception
{
  protected $code = 404;
  protected $message = 'Sorry! The resource you\'re trying to access was not found.';
}
