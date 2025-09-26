<?php
/**
 * Book Controller
 * Handles book management operations
 */

class BookController {
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
     * Display all user's books (library view)
     */
    public function index(): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        $books = $this->bookModel->getUserBooks($userId);
        
        // Add statistics for each book
        foreach ($books as &$book) {
            $book['page_count'] = $this->bookModel->getPageCount($book['id']);
            $book['word_count'] = $this->bookModel->getWordCount($book['id']);
        }
        
        view('books/index', [
            'title' => 'My Books',
            'auth' => $this->auth,
            'books' => $books
        ]);
    }
    
    /**
     * Show create book form
     */
    public function create(): void {
        $this->auth->requireAuth();
        
        view('books/create', [
            'title' => 'New Book',
            'auth' => $this->auth
        ]);
    }
    
    /**
     * Store a new book
     */
    public function store(): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        // Validate input
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $isPublic = isset($_POST['is_public']) ? 1 : 0;
        
        if (empty($title)) {
            flash('Title is required.', 'error');
            redirect('/books/new');
            return;
        }
        
        // Handle cover image upload
        $coverPath = null;
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $coverPath = $this->uploadCover($_FILES['cover']);
        }
        
        // Generate slug
        $slug = $this->bookModel->generateSlug($title);
        
        // Create book
        try {
            $bookId = $this->bookModel->create([
                'title' => $title,
                'slug' => $slug,
                'subtitle' => $subtitle,
                'author' => $author,
                'cover_path' => $coverPath,
                'is_public' => $isPublic,
                'created_by' => $userId
            ]);
            
            flash('Book created successfully!', 'success');
            redirect('/books/' . $slug . '/edit');
        } catch (Exception $e) {
            flash('Error creating book. Please try again.', 'error');
            redirect('/books/new');
        }
    }
    
    /**
     * Display a single book
     */
    public function show(string $slug): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        $book = $this->bookModel->findBySlug($slug);
        
        if (!$book || $book['created_by'] != $userId) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Get pages for this book
        $pagesModel = new Page($this->db);
        $pages = $pagesModel->getBookPages($book['id']);
        
        view('books/show', [
            'title' => $book['title'],
            'auth' => $this->auth,
            'book' => $book,
            'pages' => $pages
        ]);
    }
    
    /**
     * Show edit book form
     */
    public function edit(string $slug): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        $book = $this->bookModel->findBySlug($slug);
        
        if (!$book || $book['created_by'] != $userId) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Get pages for this book
        $pagesModel = new Page($this->db);
        $pages = $pagesModel->getBookPages($book['id']);
        
        view('books/edit', [
            'title' => 'Edit: ' . $book['title'],
            'auth' => $this->auth,
            'book' => $book,
            'pages' => $pages
        ]);
    }
    
    /**
     * Update a book
     */
    public function update(int $id): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        // Check ownership
        if (!$this->bookModel->isOwner($id, $userId)) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Validate input
        $title = trim($_POST['title'] ?? '');
        $subtitle = trim($_POST['subtitle'] ?? '');
        $author = trim($_POST['author'] ?? '');
        $isPublic = isset($_POST['is_public']) ? 1 : 0;
        
        if (empty($title)) {
            flash('Title is required.', 'error');
            redirect('/books/' . $id . '/edit');
            return;
        }
        
        // Handle cover image upload
        if (isset($_FILES['cover']) && $_FILES['cover']['error'] === UPLOAD_ERR_OK) {
            $book = $this->bookModel->find($id);
            // Delete old cover if exists
            if ($book['cover_path'] && file_exists(ltrim($book['cover_path'], '/'))) {
                unlink(ltrim($book['cover_path'], '/'));
            }
            $coverPath = $this->uploadCover($_FILES['cover']);
            $this->bookModel->updateCover($id, $coverPath);
        }
        
        // Update book
        try {
            $this->bookModel->update($id, [
                'title' => $title,
                'subtitle' => $subtitle,
                'author' => $author,
                'is_public' => $isPublic
            ]);
            
            flash('Book updated successfully!', 'success');
            redirect('/books');
        } catch (Exception $e) {
            flash('Error updating book. Please try again.', 'error');
            redirect('/books/' . $id . '/edit');
        }
    }
    
    /**
     * Delete a book
     */
    public function delete(int $id): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        // Check ownership
        if (!$this->bookModel->isOwner($id, $userId)) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Get book for cover cleanup
        $book = $this->bookModel->find($id);
        
        // Delete book
        try {
            $this->bookModel->delete($id);
            
            // Delete cover image if exists
            if ($book['cover_path'] && file_exists(ltrim($book['cover_path'], '/'))) {
                unlink(ltrim($book['cover_path'], '/'));
            }
            
            flash('Book deleted successfully!', 'success');
        } catch (Exception $e) {
            flash('Error deleting book. Please try again.', 'error');
        }
        
        redirect('/books');
    }
    
    /**
     * Toggle book visibility (public/private)
     */
    public function toggleVisibility(int $id): void {
        $this->auth->requireAuth();
        
        // Check CSRF manually for AJAX requests
        $token = $_SERVER['HTTP_X_CSRF_TOKEN'] ?? $_POST['_token'] ?? '';
        if (!$this->auth->verifyCsrf($token)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Invalid CSRF token']);
            return;
        }
        
        $userId = $this->auth->user()['id'];
        
        // Check ownership
        if (!$this->bookModel->isOwner($id, $userId)) {
            http_response_code(403);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        try {
            $this->bookModel->toggleVisibility($id);
            
            // Get the new visibility status
            $book = $this->bookModel->find($id);
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => true,
                'is_public' => $book['is_public']
            ]);
        } catch (Exception $e) {
            http_response_code(500);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Failed to update visibility']);
        }
    }
    
    /**
     * Handle cover image upload
     */
    private function uploadCover(array $file): ?string {
        // Fix: Use uploads directory directly, not public/uploads
        $uploadDir = 'uploads/covers/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Validate file type
        $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'];
        if (!in_array($file['type'], $allowedTypes)) {
            flash('Invalid file type. Please upload JPG, PNG, or WebP.', 'error');
            return null;
        }
        
        // Validate file size (max 5MB)
        if ($file['size'] > 5 * 1024 * 1024) {
            flash('File too large. Maximum size is 5MB.', 'error');
            return null;
        }
        
        // Generate unique filename preserving original extension
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        $filename = uniqid('cover_') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/covers/' . $filename;
        }
        
        flash('Error uploading file.', 'error');
        return null;
    }
}