<?php
/**
 * Router
 * Simple routing system for the application
 */

class Router {
    private Database $db;
    private Auth $auth;
    private array $config;
    private array $routes = [];
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
        $this->defineRoutes();
    }
    
    private function defineRoutes(): void {
        // Public routes
        $this->routes['GET']['/'] = 'HomeController@index';
        $this->routes['GET']['/login'] = 'AuthController@showLogin';
        $this->routes['POST']['/login'] = 'AuthController@login';
        $this->routes['POST']['/logout'] = 'AuthController@logout';
        
        // Protected routes
        $this->routes['GET']['/books'] = 'BookController@index';
        $this->routes['GET']['/books/new'] = 'BookController@create';
        $this->routes['POST']['/books'] = 'BookController@store';
        $this->routes['GET']['/books/{slug}'] = 'BookController@show';
        $this->routes['GET']['/books/{slug}/edit'] = 'BookController@edit';
        $this->routes['POST']['/books/{id}/update'] = 'BookController@update';
        $this->routes['POST']['/books/{id}/delete'] = 'BookController@delete';
        $this->routes['POST']['/books/{id}/visibility'] = 'BookController@toggleVisibility';
        
        // Pages routes
        $this->routes['GET']['/books/{book_id}/pages/new'] = 'PageController@create';
        $this->routes['POST']['/books/{book_id}/pages/store'] = 'PageController@store';
        $this->routes['GET']['/pages/{id}/edit'] = 'PageController@edit';
        $this->routes['POST']['/pages/{id}/update'] = 'PageController@update';
        $this->routes['POST']['/pages/{id}/delete'] = 'PageController@delete';
        $this->routes['POST']['/books/{book_id}/pages/reorder'] = 'PageController@reorder';
        
        // Version history routes
        $this->routes['GET']['/pages/{id}/history'] = 'PageController@history';
        $this->routes['GET']['/pages/{page_id}/version/{version}'] = 'PageController@viewVersion';
        $this->routes['GET']['/pages/{page_id}/compare/{version}'] = 'PageController@compareVersions';
        $this->routes['POST']['/pages/{page_id}/restore/{version}'] = 'PageController@restoreVersion';
        
        // Public book viewing
        $this->routes['GET']['/read/{book_slug}'] = 'ReadController@show';
        $this->routes['GET']['/read/{book_slug}/{page_slug}'] = 'ReadController@show';
    }
    
    public function handle(string $uri, string $method): void {
        // Check for exact match first
        if (isset($this->routes[$method][$uri])) {
            $this->dispatch($this->routes[$method][$uri]);
            return;
        }
        
        // Check for pattern matches
        foreach ($this->routes[$method] ?? [] as $pattern => $handler) {
            $regex = $this->patternToRegex($pattern);
            if (preg_match($regex, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $this->dispatch($handler, $matches);
                return;
            }
        }
        
        // 404 Not Found
        http_response_code(404);
        view('errors/404');
    }
    
    private function patternToRegex(string $pattern): string {
        $pattern = preg_replace('/\//', '\\/', $pattern);
        $pattern = preg_replace('/\{([a-z_]+)\}/', '([^\/]+)', $pattern);
        return '/^' . $pattern . '$/';
    }
    
    private function dispatch(string $handler, array $params = []): void {
        list($controller, $method) = explode('@', $handler);
        
        // Check if controller file exists
        $controllerFile = __DIR__ . "/controllers/$controller.php";
        if (!file_exists($controllerFile)) {
            // For now, show a simple coming soon page
            $this->showComingSoon($controller, $method);
            return;
        }
        
        require_once $controllerFile;
        
        $controllerInstance = new $controller($this->db, $this->auth, $this->config);
        
        if (!method_exists($controllerInstance, $method)) {
            http_response_code(500);
            die("Method $method not found in $controller");
        }
        
        call_user_func_array([$controllerInstance, $method], $params);
    }
    
    private function showComingSoon(string $controller, string $method): void {
        // Temporary coming soon page while we build controllers
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Strybk_ - Coming Soon</title>
            <style>
                :root {
                    --primary: #111111;
                    --indigo: #2E1A47;
                    --lime: #A8FF60;
                }
                body {
                    font-family: 'Inter', -apple-system, sans-serif;
                    background: linear-gradient(135deg, #111111 0%, #333333 100%);
                    min-height: 100vh;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    margin: 0;
                    color: white;
                }
                .container {
                    text-align: center;
                    padding: 40px;
                }
                h1 {
                    font-size: 48px;
                    margin-bottom: 16px;
                }
                p {
                    font-size: 18px;
                    opacity: 0.9;
                    margin-bottom: 32px;
                }
                .info {
                    background: rgba(255,255,255,0.1);
                    padding: 20px;
                    border-radius: 8px;
                    font-family: monospace;
                    font-size: 14px;
                }
                a {
                    color: var(--lime);
                    text-decoration: none;
                }
                a:hover {
                    text-decoration: underline;
                }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>📚 Strybk_</h1>
                <p>This page is under construction</p>
                <div class="info">
                    Controller: <?= htmlspecialchars($controller) ?><br>
                    Method: <?= htmlspecialchars($method) ?><br><br>
                    <a href="/login">Login</a> | <a href="/">← Back Home</a>
                </div>
            </div>
        </body>
        </html>
        <?php
    }
}