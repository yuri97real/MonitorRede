<?php

namespace App\Controllers;

use Core\iResponse;

class ErrorController
{
    public function index($request, iResponse $response)
    {
        return $response->status(404)->view("error");
    }
}