<?php

namespace Core;

interface iResponse
{
    public function status(int $response_code);
    public function redirect(string $route, array $query_params = []);
    public function setTemplate($template);
    public function view(string $view, array $data = []);
    public function json(array $data);
}

class Response implements iResponse
{
    private $response_code = 200, $template = "main";

    public function status(int $response_code)
    {
        $this->response_code = $response_code;
        return $this;
    }

    public function redirect(string $route, array $query_params = [])
    {
        $route = trim($route, "/");

        $query_string = empty($query_params) ? "" : ("?" . http_build_query($query_params));
        $redirect_link = BASE_URL . "/{$route}" . $query_string;

        header("Location: {$redirect_link}");

        die;
    }

    public function setTemplate($template)
    {
        $this->template = $template;
        return $this;
    }

    public function view(string $view, array $data = [])
    {
        http_response_code($this->response_code);

        extract($data);

        $view = __DIR__."/../app/Views/pages/{$view}.php";
        
        if( empty($this->template) ) {
            require_once $view;
        } else {
            require_once __DIR__."/../app/Views/templates/{$this->template}.php";
        }
    }

    public function json(array $data)
    {
        http_response_code($this->response_code);
        echo json_encode($data);
    }
}