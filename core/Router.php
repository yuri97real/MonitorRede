<?php

namespace Core;

class Router
{
    private $request_uri = "/", $routes = [
        "GET"=> [],
        "POST"=> [],
        "PUT"=> [],
        "DELETE"=> [],
    ];

    public function __construct()
    {
        $query_string = $_SERVER["QUERY_STRING"] ?? "";
        $query_string = "?{$query_string}";

        $base_url = (object) parse_url(BASE_URL);

        $request_uri = str_replace([
            $base_url->path,
            $query_string,
        ], "", $_SERVER["REQUEST_URI"]);

        $request_uri = rtrim($request_uri, "/");

        $this->request_uri = empty($request_uri) ? "/" : $request_uri;

        $this->checkIfIsFile();
    }

    public function get(string $route, string $action)
    {
        $this->routes["GET"][$route]["action"] = $action;
    }

    public function post(string $route, string $action)
    {
        $this->routes["POST"][$route]["action"] = $action;
    }

    public function put(string $route, string $action)
    {
        $this->routes["PUT"][$route]["action"] = $action;
    }

    public function delete(string $route, string $action)
    {
        $this->routes["DELETE"][$route]["action"] = $action;
    }

    public function middleware()
    {}

    public function dispatch()
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        $http_method_routes = $this->routes[$request_method];

        $routeData = $http_method_routes[$this->request_uri] ?? $this->extractRouteData($http_method_routes);

        [ $controller, $method ] = explode("::", $routeData["action"]);

        require_once __DIR__."/../app/Controllers/{$controller}.php";

        $controller_path = "App\\Controllers\\{$controller}";
        $instance = new $controller_path;

        $params = $routeData["params"] ?? [];

        call_user_func([ $instance, $method ], new Request($params), new Response());
    }

    private function checkIfIsFile()
    {
        $filename = __DIR__."/../public{$this->request_uri}";
        $is_file = file_exists($filename) && !is_dir($filename);

        if( !$is_file ) return;

        session_cache_limiter("public");
        session_start();

        $mime = mime_content_type($filename);
        $mime = $mime == "text/plain" ? "" : $mime;

        header("Content-Type: {$mime}");
        readfile($filename);

        die;
    }

    private function extractRouteData(array $routes)
    {
        $request_uri_pieces = explode("/", $this->request_uri);

        foreach($routes as $route => $data)
        {
            $route_pieces = explode("/", $route);

            if( count($route_pieces) != count($request_uri_pieces) ) continue;

            $num_params = substr_count($route, "{");
            $routes_diff = array_diff_assoc($route_pieces, $request_uri_pieces);

            if( $num_params != count($routes_diff) ) continue;

            foreach($routes_diff as $key => $param)
            {
                $param = str_replace([ "{", "}" ], "", $param);
                $data["params"][$param] = $request_uri_pieces[$key];
            }

            return $data;
        }

        return [ "action"=> "ErrorController::index" ];
    }
}