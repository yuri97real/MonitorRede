<?php

namespace Core;

class Request
{
    public $query, $params, $body;

    public function __construct(array $params = [])
    {
        $this->query = (object) $_GET;
        $this->params = (object) $params;
        $this->body = $this->extractBodyData();
    }

    private function extractBodyData()
    {
        $request_method = $_SERVER["REQUEST_METHOD"];
        $content_type = $_SERVER["CONTENT_TYPE"] ?? "";

        if( $request_method == "POST" ) {
            return (object) $_POST;
        }

        if( $request_method != "PUT" ) {
            return (object) [];
        }

        $put_body = file_get_contents("php://input");

        if( $content_type === "application/json" ) {
            return json_decode($put_body);
        }

        if( $content_type === "application/x-www-form-urlencoded" ) {
            parse_str($put_body, $output);
            return (object) $output;
        }
        
        [ $boundary ] = explode("\r\n", $put_body);

        $content = str_replace([ $boundary, "\r\n" ], "", $put_body);
        $content = trim($content, "-");

        $content_pieces = explode('Content-Disposition: form-data; name="', $content);
        $content_pieces = array_filter($content_pieces);

        $str_vars = array_map(function($piece) {
            [ $key, $value ] = explode('"', $piece);
            return "{$key}={$value}";
        }, $content_pieces);

        $str_vars = implode("&", $str_vars);

        parse_str($str_vars, $output);

        return (object) $output;
    }
}