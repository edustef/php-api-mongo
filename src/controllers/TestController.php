<?php

namespace api\controllers;

use edustef\mvcFrame\Controller;
use edustef\mvcFrame\Request;
use edustef\mvcFrame\Response;
use api\models\Prestamo;
use edustef\mvcFrame\Application;
use edustef\mvcFrame\exceptions\NotFoundException;

class TestController extends Controller
{

  public function getTests(Request $request, Response $response)
  {
    $results = [];
    $db = Application::$app->database;

    $cursor = $db->test->find();

    foreach ($cursor as $test) {
      $results[] = $test;
    }

    return $response->json($results);
  }

  public function getTest(Request $request, Response $response, $params)
  {
    $db = Application::$app->database;

    $result = $db->test->findOne(['name' => 'test1']);

    return $response->json($result);
  }
}
