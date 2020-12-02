<?php

namespace Aqua;

class Router {
    //Settings on handling Requests
    private string $param_symbol = ':';
    private array $allowed_methods = ['get', 'post', 'put', 'delete'];

    //Request information
    private string $req_method;
    private string $req_url;
    private $req_body;
    private $req_params;

    //Items that will be added to the callback
    private $callback_items;

    //Found a route
    public bool $routeFound = false;

    function __construct() {
        $this->req_method = strtolower($_SERVER['REQUEST_METHOD']);
        $this->req_body = json_decode(file_get_contents('php://input'), true);

        $url = parse_url($_SERVER['REQUEST_URI']);
        $this->req_url = $url['path'];
        parse_str($url['query'], $this->req_params);
    }

    function options(array $options) {
        foreach ($options as $key => $value) {
            if (!empty($value)) $this->$key = $value;
        }
    }

    function use(string $name, $item) {
        $this->callback_items[$name] = $item;
    }

    function respond(string $method, string $path, $callback) {
        if($this->routeFound) return;

        $method = strtolower($method);
        if ($method != $this->req_method) return;

        $func_split = explode('/', $this->trimPath($path));
        $req_split = explode('/', $this->trimPath($this->req_url));

        if (count($func_split) != count($req_split)) return;

        $diff = array_diff($req_split, $func_split);

        $params = [];
        foreach ($diff as $key => $value) {
            $var = $func_split[$key];
            if (strpos($var, $this->param_symbol) === 0) {
                $name = ltrim($var, $this->param_symbol);
                $params[$name] = $value;
                continue;
            }
            return;
        }

        foreach ($this->callback_items as $key => $value) {
            $params[$key] = $value;
        }

        $params['params'] = $this->req_params;
        $params['body'] = $this->req_body;

        call_user_func($callback, $params);
        $this->routeFound = true;
    }

    private function trimPath(string $path) {
        $path = rtrim($path, '?');
        $path = rtrim($path, '/');

        return $path;
    }
}
