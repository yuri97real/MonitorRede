<?php

namespace App\Controllers;

use Core\iResponse;
use App\Models\HostModel;

class HostController
{
    public function index($request, iResponse $response)
    {
        return $response->view("hosts");
    }

    public function getHosts($request, iResponse $response)
    {
        $result = (new HostModel)->getHosts();
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }
}