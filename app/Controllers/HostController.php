<?php

namespace App\Controllers;

use Core\iResponse;

class HostController
{
    public function index($request, iResponse $response)
    {
        return $response->view("hosts");
    }

    public function getHosts($request, iResponse $response)
    {
        return $response->json([]);
    }
}