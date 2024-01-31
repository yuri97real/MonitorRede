<?php

namespace Core;

interface iRequest
{
    public function only(array $keys);
    public function expectsJson();
}

class Request implements iRequest
{
    public $query, $params, $body, $content_type;

    public function __construct(array $params = [])
    {
        $this->content_type = $_SERVER["CONTENT_TYPE"] ?? "";
        
        $this->query = (object) $_GET;
        $this->params = (object) $params;

        $this->body = $this->extractBodyData();
    }

    public function only(array $keys)
    {
        $data = [];

        foreach($keys as $key)
        {
            if( !isset($this->body->$key) ) continue;
            $data[$key] = $this->body->$key;
        }

        return $data;
    }

    public function expectsJson()
    {
        return $this->content_type === "application/json";
    }

    private function extractBodyData()
    {
        $body = file_get_contents("php://input");

        if( $this->content_type === "application/json" ) {
            return json_decode($body);
        }

        if( $this->content_type === "application/x-www-form-urlencoded" ) {
            parse_str($body, $output);
            return (object) $output;
        }
        
        [ $boundary ] = explode("\r\n", $body);

        $content = str_replace([ $boundary, "\r\n" ], "", $body);
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