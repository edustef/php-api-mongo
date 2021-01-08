<?php

namespace edustef\mvcFrame\middlewares;

use edustef\mvcFrame\Application;
use edustef\mvcFrame\exceptions\ForbiddenException;

class AuthMiddleware extends Middleware
{

  public array $actions;

  public function __construct(array $actions = [])
  {
    $this->actions = $actions;
  }

  public function execute($cb = null)
  {
    if (Application::$app->isGuest()) {
      if (empty($this->actions) || in_array(Application::$app->controller->action, $this->actions)) {
        if (!$cb) {
          throw new ForbiddenException();
        }

        $cb();
      }
    }
  }
}
