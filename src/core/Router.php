<?php

namespace edustef\mvcFrame;

use edustef\mvcFrame\exceptions\NotFoundException;

class Router
{
  /**
   * This stores an array of arrays with the format 
   */
  protected array $routes = [];
  public Request $request;
  public Response $response;

  public function __construct(Request $request, Response $response)
  {
    $this->response = $response;
    $this->request = $request;
  }

  public function get($path, $callback)
  {
    $this->routes[$this->request::GET][$path] = $callback;
  }

  public function post($path, $callback)
  {
    $this->routes[$this->request::POST][$path] = $callback;
  }

  public function put($path, $callback)
  {
    $this->routes[$this->request::PUT][$path] = $callback;
  }

  public function patch($path, $callback)
  {
    $this->routes[$this->request::PATCH][$path] = $callback;
  }

  public function delete($path, $callback)
  {
    $this->routes[$this->request::DELETE][$path] = $callback;
  }

  /**
   * Will resolve the method and path of the REQUEST
   * and will create the controller and run it's method referenced by the callback.
   * @throws NotFoundException; 
   */
  public function resolve(): string
  {
    $path = $this->request->getPath();
    $method = $this->request->method();

    $callback = $this->routes[$method][$path] ?? false;
    $params = [];

    if ($callback === false) {
      throw new NotFoundException();
    }

    //create instance of controller
    if (is_array($callback)) {
      $controller = new $callback[0]();
      Application::$app->controller = $controller;
      $controller->action = $callback[1];
      $callback[0] = $controller;
    }

    return call_user_func($callback, $this->request, $this->response, $params);
  }
}
