<?php

namespace App\Controllers;

use Core\iResponse;

use App\Libs\Ping;

class PingController
{
    public function index($request, iResponse $response)
    {
        [ "host"=> $host ] = get_object_vars($request->params);

        $ping = new Ping($host);

        $result = $ping->run();

        return $response->json($result);
    }
}