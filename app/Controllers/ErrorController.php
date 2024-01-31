<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

class ErrorController
{
    public function index(iRequest $request, iResponse $response)
    {
        $response->status(404);

        if( $request->expectsJson() ) return $response->json([
            "error"=> "Page not found",
        ]);

        return $response->view("error");
    }
}