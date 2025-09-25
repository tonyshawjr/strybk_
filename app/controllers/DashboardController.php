<?php
/**
 * Dashboard Controller
 * Handles the main dashboard page
 */

class DashboardController {
    private Database $db;
    private Auth $auth;
    private Book $bookModel;
    private array $config;
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
        $this->bookModel = new Book($db);
    }
    
    /**
     * Display the dashboard
     */
    public function index(): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        // Get user's books
        $books = $this->bookModel->getUserBooks($userId);
        
        // Calculate statistics
        $totalBooks = count($books);
        $publicBooks = 0;
        $totalPages = 0;
        $totalWords = 0;
        
        foreach ($books as &$book) {
            if ($book['is_public']) {
                $publicBooks++;
            }
            $pageCount = $this->bookModel->getPageCount($book['id']);
            $wordCount = $this->bookModel->getWordCount($book['id']);
            
            $totalPages += $pageCount;
            $totalWords += $wordCount;
            
            $book['page_count'] = $pageCount;
            $book['word_count'] = $wordCount;
        }
        
        // Get recent books (last 5)
        $recentBooks = array_slice($books, 0, 5);
        
        view('dashboard/index', [
            'title' => 'Dashboard',
            'auth' => $this->auth,
            'stats' => [
                'total_books' => $totalBooks,
                'public_books' => $publicBooks,
                'total_pages' => $totalPages,
                'total_words' => $totalWords
            ],
            'recent_books' => $recentBooks
        ]);
    }
}