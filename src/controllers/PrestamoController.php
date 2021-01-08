<?php

namespace api\controllers;

use edustef\mvcFrame\Controller;
use edustef\mvcFrame\Request;
use edustef\mvcFrame\Response;
use api\models\Prestamo;
use edustef\mvcFrame\exceptions\NotFoundException;

class PrestamoController extends Controller
{

  public function getPrestamos(Request $request, Response $response)
  {
    $body = $request->getBody();
    $prestamos = [];

    if (isset($body['query']) && $body['query'] !== '') {
      $attributes = ['c.dni', 'p.estado', 'l.titulo', 'c.nombre'];
      $prestamos = Prestamo::search($attributes, $body['query']);
    } else {
      $prestamos = Prestamo::findAll();
    }
    return $response->json($prestamos);
  }

  public function postPrestamo(Request $request, Response $response)
  {
    $prestamo = new Prestamo();
    $prestamo->loadData($request->getBody());

    if ($prestamo->validate() && $prestamo->save()) {
      $response->setStatusCode(201);
      return $response->json(['status' => 'ok', 'message' => 'Created successfully']);
    }

    return $response->json([
      'errors' => $prestamo->errors
    ]);
  }

  public function deletePrestamo(Request $request, Response $response)
  {
    if (Prestamo::delete($request->getBody())) {
      $response->setStatusCode(204);
      return $response->json(['status' => 'ok', 'Deleted successfully']);
    }

    throw new NotFoundException('No Prestamo found with that DNI');
  }

  public function editPrestamo(Request $request, Response $response)
  {
    $body = $request->getBody();
    $where = ['id' => $body['id']];
    $prestamo = Prestamo::findOne($where);

    if ($prestamo->update($body, $where)) {
      $response->setStatusCode(204);
      return $response->json(['status' => 'ok', 'message' => 'Updated successfully']);
    }

    return $response->json([
      'errors' => $prestamo->errors
    ]);
  }
}
