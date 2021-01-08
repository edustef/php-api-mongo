<?php

namespace edustef\mvcFrame;

use edustef\mvcFrame\middlewares\Middleware;

class Controller
{
  public array $middlewares = [];
  public string $action = '';

  public function registerMiddleware(Middleware $middleware, $cb = null)
  {
    $this->middlewares[] = [$middleware, $cb];
  }
}
