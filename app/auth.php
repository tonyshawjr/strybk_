<?php
/**
 * Authentication System
 * Handles user login, logout, and session management
 */

class Auth {
    private Database $db;
    private array $config;
    private ?array $user = null;
    
    public function __construct(Database $db, array $config) {
        $this->db = $db;
        $this->config = $config;
        $this->checkSession();
    }
    
    private function checkSession(): void {
        if (isset($_SESSION['user_id'])) {
            $this->user = $this->db->selectOne(
                "SELECT * FROM users WHERE id = ?",
                [$_SESSION['user_id']]
            );
            
            if (!$this->user) {
                $this->logout();
            }
        }
    }
    
    public function attempt(string $email, string $password): bool {
        $user = $this->db->selectOne(
            "SELECT * FROM users WHERE email = ?",
            [$email]
        );
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $this->login($user);
            return true;
        }
        
        return false;
    }
    
    private function login(array $user): void {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['login_time'] = time();
        
        // Regenerate session ID for security
        session_regenerate_id(true);
        
        $this->user = $user;
    }
    
    public function logout(): void {
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }
        
        session_destroy();
        $this->user = null;
    }
    
    public function check(): bool {
        return $this->user !== null;
    }
    
    public function guest(): bool {
        return !$this->check();
    }
    
    public function user(): ?array {
        return $this->user;
    }
    
    public function id(): ?int {
        return $this->user['id'] ?? null;
    }
    
    public function requireAuth(): void {
        if (!$this->check()) {
            $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            header('Location: /login');
            exit;
        }
    }
    
    public function requireGuest(): void {
        if ($this->check()) {
            header('Location: /dashboard');
            exit;
        }
    }
    
    public function csrfToken(): string {
        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
    
    public function verifyCsrf(string $token): bool {
        return isset($_SESSION['csrf_token']) && 
               hash_equals($_SESSION['csrf_token'], $token);
    }
    
    public function checkCsrf(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $token = $_POST[$this->config['security']['csrf_token_name']] ?? '';
            if (!$this->verifyCsrf($token)) {
                http_response_code(403);
                die('CSRF token validation failed');
            }
        }
    }
}