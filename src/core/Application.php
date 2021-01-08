<?php

namespace edustef\mvcFrame;

class Application
{
  public static Application $app;

  public Router $router;
  public Request $request;
  public Response $response;
  public Session $session;
  // Controller is instanciated in Router class 
  public ?Controller $controller = null;

  // CONFIG Variables
  public ?Database $database = null;
  public ?DatabaseModel $user = null;

  public function __construct(array $config)
  {
    self::$app = $this;

    $this->request = new Request();
    $this->response = new Response();
    $this->session = new Session();
    $this->router = new Router($this->request, $this->response);

    if (isset($config['db'])) {
      $this->database = new Database($config['db']);
    }
  }

  public function run()
  {
    try {
      echo $this->router->resolve();
    } catch (\Exception $e) {
      // if (is_numeric($e->getCode())) {
      //   echo $this->response->json([
      //     'errorMessage' => $e->getMessage()
      //   ], $e->getCode());
      //   return;
      // }

      // echo $this->response->json([
      //   'errorMessage' => $e->getMessage()
      // ]);
      echo $this->response->json($e);
    }
  }
}
