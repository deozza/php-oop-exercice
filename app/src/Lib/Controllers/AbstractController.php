<?php

namespace App\Lib\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;

abstract class AbstractController
{
  abstract public function process(Request $request): Response;

  protected function render(array $data = []): Response
  {
    $response = new Response();
    $response->setContent(json_encode($data));
    $response->addHeader('Content-Type', 'application/json');

    return $response;
  }
}
