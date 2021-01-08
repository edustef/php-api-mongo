<?php

namespace edustef\mvcFrame;

class Request
{
  public const GET = 'GET';
  public const POST = 'POST';
  public const PUT = 'PUT';
  public const PATCH = 'PATCH';
  public const DELETE = 'DELETE';
  private array $body;

  public function __construct()
  {
    $this->setBody();
  }

  public function method(): string
  {
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === self::POST && isset($this->body['_method'])) {
      $method = strtoupper($this->body['_method']);
    }

    return $method;
  }

  public function isGet(): bool
  {
    return $this->method() === self::GET;
  }

  public function isPost(): bool
  {
    return $this->method() === self::POST;
  }

  public function isPut(): bool
  {
    return $this->method() === self::POST;
  }

  public function isDelete(): bool
  {
    return $this->method() === self::POST;
  }

  public function getPath(): string
  {
    $path = $_SERVER['REQUEST_URI'] ?? '/';
    $position = strpos($path, '?');


    if ($position === false) {
      return $path;
    }

    return substr($path, 0, $position);
  }

  public function setBody()
  {
    $body = [];
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === self::GET) {
      foreach (array_keys($_GET) as $key) {
        $body[$key] = filter_input(INPUT_GET, $key, FILTER_SANITIZE_STRING);
      }
      // $body = filter_input_array(INPUT_GET, FILTER_SANITIZE_STRING);
    } else { // is post
      foreach (array_keys($_POST) as $key) {
        $body[$key] = filter_input(INPUT_POST, $key, FILTER_SANITIZE_STRING);
      }
    }

    $this->body = $body;
  }

  public function getBody(): array
  {
    $body = $this->body;
    unset($body['_method']);
    return $body;
  }
}
