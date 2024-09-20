<?php

declare(strict_types=1);

namespace KnowledgeCity;

class Routes { 
    
    private array $routes = [];
    private Container $container;

    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    public function add(string $method, string $path, array $controller) { 
        
        $path = $this->normalizePath($path);
        $this->routes[] = [
            'path' => $path,
            'method' => strtoupper($method),
            'controller' => $controller,
            'middlewares' => []
        ];

    }

    private function normalizePath(string $path): string { 
        
        $path = trim($path, '/'); 
        $path = "/{$path}/"; 
        $path = preg_replace('#[/]{2,}#', '/', $path);
        return $path;

    }

    private function extractParameters(string $pattern, string $path): array
    {
        $parameterNames = [];
        $pattern = preg_replace_callback('/\{(\w+)\}/', function ($matches) use (&$parameterNames) {
            $parameterNames[] = $matches[1];
            return '([^/]+)';
        }, $pattern);

        // Escape slashes
        $pattern = str_replace('/', '\/', $pattern);

        // Match the URL path with the pattern
        if (preg_match("/^{$pattern}$/", $path, $matches)) {
            array_shift($matches); // Remove the full match
            return array_combine($parameterNames, $matches);
        }

        return [];
    }

    public function dispatch(string $path) { 
        
        $path = $this->normalizePath($path); 
        $method = strtoupper($_SERVER['REQUEST_METHOD']);

        foreach ($this->routes as $route) {

            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route['path']);
            $pattern = str_replace('/', '\/', $pattern);
            
            if (!preg_match("/^{$pattern}$/", $path)) {
                continue;
            }

            if ($route['method'] !== $method) {
                continue;
            }
            
            $params = $this->extractParameters($route['path'], $path);

            [$class, $function] = $route['controller'];
            $controllerInstance = $this->container->make($class);

            call_user_func([$controllerInstance, $function], $params );

            return;
            
        }
        
        http_response_code(404);
        echo "Route not found";
    }
    
} 
