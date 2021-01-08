<?php

namespace edustef\mvcFrame;

class Session
{
  protected const FLASH_KEY = 'session_messages';

  public function __construct()
  {
    session_start();
    $sessionMessages = $_SESSION['session_messages'] ?? [];
    foreach ($sessionMessages as &$sessionMessage) {
      //mark to be removed
      $sessionMessage['remove'] = true;
    }

    $_SESSION['session_messages'] = $sessionMessages;
  }

  public function get($key)
  {
    return $_SESSION[$key] ?? false;
  }

  public function set($key, $value)
  {
    $_SESSION[$key] = $value;
  }

  public function remove($key)
  {
    unset($_SESSION[$key]);
  }

  public function getFlashSession($key)
  {
    return $_SESSION[self::FLASH_KEY][$key]['value'] ?? [];
  }

  public function setFlashSession($key, $message)
  {
    $_SESSION[self::FLASH_KEY][$key] = [
      'remove' => false,
      'value' => $message
    ];
  }

  public function __destruct()
  {
    $sessionMessages = $_SESSION['session_messages'] ?? [];
    foreach ($sessionMessages as $key => &$sessionMessage) {
      if ($sessionMessage['remove'] === true) {
        unset($sessionMessages[$key]);
      }
    }

    $_SESSION['session_messages'] = $sessionMessages;
  }
}
