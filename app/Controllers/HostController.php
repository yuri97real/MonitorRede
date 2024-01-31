<?php

namespace App\Controllers;

use Core\iRequest;
use Core\iResponse;

use App\Models\HostModel;

class HostController
{
    public function index(iRequest $request, iResponse $response)
    {
        return $response->view("hosts");
    }

    public function getHosts(iRequest $request, iResponse $response)
    {
        $result = (new HostModel)->getHosts();
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function createHost(iRequest $request, iResponse $response)
    {
        $data = $request->only([
            "host",
            "description",
            "department",
            "time_interval",
            "time_unit",
            "max_attempts",
            "category_id",
        ]);

        $result = (new HostModel)->createHost($data);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function updateHost(iRequest $request, iResponse $response)
    {
        [ "host_id"=> $host_id ] = get_object_vars($request->params);

        $data = $request->only([
            "host",
            "description",
            "department",
            "time_interval",
            "time_unit",
            "max_attempts",
            "category_id",
        ]);

        $result = (new HostModel)->updateHost($host_id, $data);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }

    public function deleteHost(iRequest $request, iResponse $response)
    {
        [ "host_id"=> $host_id ] = get_object_vars($request->params);

        $result = (new HostModel)->deleteHost($host_id);
        $status = $result["error"] ? 500 : 200;

        return $response->status($status)->json($result);
    }
}