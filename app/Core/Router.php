<?php
namespace App\Core;

// Enrutador simple para API y vistas
class Router
{
    protected array $routes = [];

    public function get(string $uri, $action): void { $this->add('GET', $uri, $action); }
    public function post(string $uri, $action): void { $this->add('POST', $uri, $action); }
    public function put(string $uri, $action): void { $this->add('PUT', $uri, $action); }
    public function delete(string $uri, $action): void { $this->add('DELETE', $uri, $action); }
    public function any(string $uri, $action): void { foreach (['GET','POST','PUT','DELETE'] as $m) $this->add($m, $uri, $action); }

    protected function add(string $method, string $uri, $action): void
    {
        $this->routes[] = ['method' => $method, 'uri' => $uri, 'action' => $action];
    }

    public function dispatch(Request $request): void
    {
        $method = $request->method;
        // support _method override for forms
        if ($method === 'POST' && isset($request->body['_method'])) {
            $method = strtoupper($request->body['_method']);
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) continue;
            $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $route['uri']);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $request->uri, $matches)) {
                foreach ($matches as $k => $v) {
                    if (is_string($k)) $request->params[$k] = $v;
                }
                $action = $route['action'];
                if (is_callable($action)) {
                    call_user_func($action, $request);
                    return;
                }
                if (is_string($action) && strpos($action, '@') !== false) {
                    [$controller, $method] = explode('@', $action);
                    $fqcn = 'App\\Controllers\\' . $controller;
                    $instance = new $fqcn();
                    $instance->{$method}($request);
                    return;
                }
                return;
            }
        }
        Response::json(['error' => 'Not Found', 'path' => $request->uri], 404);
    }
}
