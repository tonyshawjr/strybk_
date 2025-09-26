<?php
/**
 * Home Controller
 * Handles the homepage and public-facing pages
 */

class HomeController {
    private Database $db;
    private Auth $auth;
    private array $config;
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
    }
    
    /**
     * Display the homepage
     */
    public function index(): void {
        // If user is logged in, redirect to dashboard
        if ($this->auth->check()) {
            redirect('/dashboard');
            return;
        }
        
        // Show homepage
        require __DIR__ . '/../views/home/index.php';
    }
}