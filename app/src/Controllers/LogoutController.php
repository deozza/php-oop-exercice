<?php

namespace App\Controllers;

use App\Lib\Http\Request;
use App\Lib\Http\Response;
use App\Lib\Controllers\AbstractController;

class LogoutController extends AbstractController {
    public function process(Request $request): Response {
        session_destroy();
        return $this->redirect('/');
    }
}