<?php

namespace api\controllers;

use edustef\mvcFrame\Controller;
use edustef\mvcFrame\Request;
use edustef\mvcFrame\Response;
use api\models\Libro;
use edustef\mvcFrame\exceptions\NotFoundException;

class LibroController extends Controller
{

  public function getLibros(Request $request, Response $response)
  {
    $body = $request->getBody();
    $libros = [];

    if (isset($body['query']) && $body['query'] !== '') {
      $attributes = ['titulo', 'subtitulo', 'categoria', 'editorial', 'autor'];
      $value = $body['query'];
      $libros = Libro::search($attributes, $value);
    } else {
      $libros = Libro::findAll();
    }
    return $response->json($libros);
  }

  public function postLibro(Request $request, Response $response)
  {
    $libro = new Libro();
    $libro->loadData($request->getBody());

    if ($libro->validate() && $libro->save()) {
      $response->setStatusCode(201);
      return $response->json(['status' => 'ok', 'message' => 'Created successfully']);
    }

    return $response->json([
      'errors' => $libro->errors
    ]);
  }

  public function deleteLibro(Request $request, Response $response)
  {
    if (Libro::delete($request->getBody())) {
      $response->setStatusCode(204);
      return $response->json(['status' => 'ok', 'Deleted successfully']);
    }

    throw new NotFoundException('No libro found with that DNI');
  }

  public function editLibro(Request $request, Response $response)
  {
    $body = $request->getBody();
    $where = ['isbn' => $body['isbn']];
    $libro = Libro::findOne($where);

    if ($libro->update($body, $where)) {
      $response->setStatusCode(204);
      return $response->json(['status' => 'ok', 'message' => 'Updated successfully']);
    }

    return $response->json([
      'errors' => $libro->errors
    ]);
  }
}
