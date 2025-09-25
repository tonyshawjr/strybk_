<?php
/**
 * Authentication Controller
 * Handles login, logout, and authentication pages
 */

class AuthController {
    private Database $db;
    private Auth $auth;
    private array $config;
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
    }
    
    public function showLogin(): void {
        $this->auth->requireGuest();
        view('auth/login', [
            'title' => 'Login',
            'auth' => $this->auth,
            'error' => $_SESSION['login_error'] ?? null
        ]);
        unset($_SESSION['login_error']);
    }
    
    public function login(): void {
        $this->auth->requireGuest();
        $this->auth->checkCsrf();
        
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        
        if ($this->auth->attempt($email, $password)) {
            // Redirect to intended page or dashboard
            $redirect = $_SESSION['redirect_after_login'] ?? '/dashboard';
            unset($_SESSION['redirect_after_login']);
            redirect($redirect);
        } else {
            $_SESSION['login_error'] = 'Invalid email or password.';
            $_SESSION['old'] = ['email' => $email];
            redirect('/login');
        }
    }
    
    public function logout(): void {
        $this->auth->checkCsrf();
        $this->auth->logout();
        flash('You have been logged out.', 'success');
        redirect('/login');
    }
}