//Router.php
<?php
class Router {
    private array $routes = [];

    // Ajoute une nouvelle route à notre collection
    public function add(string $method, string $path, callable|array $handler): void {
        // Convertit les chemins avec des paramètres (ex: /jpos/{id}) en une expression régulière
        $path = preg_replace('/\{([\w]+)\}/', '(?P<$1>[^/]+)', $path);
        $this->routes[] = ['method' => $method, 'path' => '#^' . $path . '$#', 'handler' => $handler];
    }

    // Cherche la route correspondante et exécute le handler associé
    public function dispatch(): void {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $requestMethod = $_SERVER['REQUEST_METHOD'];

        foreach ($this->routes as $route) {
            // Vérifie si la méthode et le chemin correspondent
            if ($route['method'] === $requestMethod && preg_match($route['path'], $requestUri, $matches)) {
                // Extrait les paramètres de l'URL (ex: l'ID de /jpos/5)
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                
                // Appelle la méthode du contrôleur en lui passant les paramètres
                call_user_func_array($route['handler'], $params);
                return;
            }
        }

        // Si aucune route ne correspond, on renvoie une erreur 404
        http_response_code(404);
        echo json_encode(['error' => 'Route non trouvée']);
    }
}
