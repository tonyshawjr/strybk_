<?php
/**
 * Page Controller
 * Handles page management operations for books
 */

class PageController {
    private Database $db;
    private Auth $auth;
    private Book $bookModel;
    private Page $pageModel;
    private array $config;
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
        $this->bookModel = new Book($db);
        $this->pageModel = new Page($db);
    }
    
    /**
     * Show create page form
     */
    public function create(int $bookId): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        // Check book ownership
        if (!$this->bookModel->isOwner($bookId, $userId)) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        $book = $this->bookModel->find($bookId);
        
        // Get existing pages for position ordering
        $pages = $this->pageModel->getBookPages($bookId);
        $nextPosition = count($pages) + 1;
        
        view('pages/create', [
            'title' => 'New Page',
            'auth' => $this->auth,
            'book' => $book,
            'next_position' => $nextPosition
        ]);
    }
    
    /**
     * Store a new page
     */
    public function store(int $bookId): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        // Check book ownership
        if (!$this->bookModel->isOwner($bookId, $userId)) {
            flash('Book not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Validate input
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $kind = $_POST['kind'] ?? 'chapter';
        $position = (int)($_POST['position'] ?? 0);
        
        if (empty($title)) {
            flash('Title is required.', 'error');
            redirect('/books/' . $bookId . '/pages/new');
            return;
        }
        
        // Validate page type
        $allowedKinds = ['chapter', 'section', 'picture', 'divider'];
        if (!in_array($kind, $allowedKinds)) {
            $kind = 'chapter';
        }
        
        // For picture pages, handle image upload and store path in content
        $imagePath = null;
        if ($kind === 'picture' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $imagePath = $this->uploadImage($_FILES['image']);
            if ($imagePath) {
                $content = $imagePath; // Store image path in content field for picture pages
            }
        }
        
        // Generate slug
        $slug = $this->pageModel->generateSlug($title, $bookId);
        
        // Calculate word count for chapter/section content
        $wordCount = 0;
        if (in_array($kind, ['chapter', 'section'])) {
            $wordCount = str_word_count(strip_tags($content));
        }
        
        // Create page
        try {
            $pageId = $this->pageModel->create([
                'book_id' => $bookId,
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'kind' => $kind,
                'position' => $position,
                'word_count' => $wordCount
            ]);
            
            flash('Page created successfully!', 'success');
            redirect('/pages/' . $pageId . '/edit');
        } catch (Exception $e) {
            flash('Error creating page. Please try again.', 'error');
            redirect('/books/' . $bookId . '/pages/new');
        }
    }
    
    /**
     * Show edit page form
     */
    public function edit(int $id): void {
        $this->auth->requireAuth();
        $userId = $this->auth->user()['id'];
        
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Check book ownership
        if (!$this->bookModel->isOwner($page['book_id'], $userId)) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        $book = $this->bookModel->find($page['book_id']);
        
        view('pages/edit', [
            'title' => 'Edit: ' . $page['title'],
            'auth' => $this->auth,
            'page' => $page,
            'book' => $book
        ]);
    }
    
    /**
     * Update a page
     */
    public function update(int $id): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Check book ownership
        if (!$this->bookModel->isOwner($page['book_id'], $userId)) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Validate input
        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $kind = $_POST['kind'] ?? $page['kind'];
        $position = (int)($_POST['position'] ?? $page['position']);
        
        if (empty($title)) {
            flash('Title is required.', 'error');
            redirect('/pages/' . $id . '/edit');
            return;
        }
        
        // Handle image upload for picture pages
        if ($kind === 'picture' && isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            // Delete old image if exists (stored in content field for picture pages)
            if ($page['kind'] === 'picture' && $page['content'] && file_exists('public' . $page['content'])) {
                unlink('public' . $page['content']);
            }
            $imagePath = $this->uploadImage($_FILES['image']);
            if ($imagePath) {
                $content = $imagePath; // Store new image path in content field
            }
        }
        
        // Calculate word count
        $wordCount = 0;
        if (in_array($kind, ['chapter', 'section'])) {
            $wordCount = str_word_count(strip_tags($content));
        }
        
        // Update page
        try {
            $this->pageModel->update($id, [
                'title' => $title,
                'content' => $content,
                'kind' => $kind,
                'position' => $position,
                'word_count' => $wordCount
            ]);
            
            flash('Page updated successfully!', 'success');
            
            // Get book for redirect
            $book = $this->bookModel->find($page['book_id']);
            redirect('/books/' . $book['slug'] . '/edit');
        } catch (Exception $e) {
            flash('Error updating page. Please try again.', 'error');
            redirect('/pages/' . $id . '/edit');
        }
    }
    
    /**
     * Delete a page
     */
    public function delete(int $id): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        $page = $this->pageModel->find($id);
        
        if (!$page) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Check book ownership
        if (!$this->bookModel->isOwner($page['book_id'], $userId)) {
            flash('Page not found.', 'error');
            redirect('/books');
            return;
        }
        
        // Delete page
        try {
            $this->pageModel->delete($id);
            
            // Delete image if exists (stored in content field for picture pages)
            if ($page['kind'] === 'picture' && $page['content'] && file_exists('public' . $page['content'])) {
                unlink('public' . $page['content']);
            }
            
            flash('Page deleted successfully!', 'success');
        } catch (Exception $e) {
            flash('Error deleting page. Please try again.', 'error');
        }
        
        // Get book for redirect
        $book = $this->bookModel->find($page['book_id']);
        redirect('/books/' . $book['slug'] . '/edit');
    }
    
    /**
     * Reorder pages
     */
    public function reorder(int $bookId): void {
        $this->auth->requireAuth();
        $this->auth->checkCsrf();
        
        $userId = $this->auth->user()['id'];
        
        // Check book ownership
        if (!$this->bookModel->isOwner($bookId, $userId)) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            return;
        }
        
        // Get new order from POST data
        $pageIds = $_POST['page_ids'] ?? [];
        
        if (empty($pageIds)) {
            http_response_code(400);
            echo json_encode(['error' => 'No pages provided']);
            return;
        }
        
        // Update positions
        try {
            foreach ($pageIds as $position => $pageId) {
                $this->pageModel->updatePosition((int)$pageId, $position + 1);
            }
            
            http_response_code(200);
            echo json_encode(['success' => true]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['error' => 'Failed to reorder pages']);
        }
    }
    
    /**
     * Handle image upload
     */
    private function uploadImage(array $file): ?string {
        $uploadDir = 'public/uploads/pages/';
        
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
        
        // Validate file size (max 10MB for pages)
        if ($file['size'] > 10 * 1024 * 1024) {
            flash('File too large. Maximum size is 10MB.', 'error');
            return null;
        }
        
        // Generate unique filename
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = uniqid('page_') . '.' . $extension;
        $filepath = $uploadDir . $filename;
        
        // Move uploaded file
        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return '/uploads/pages/' . $filename;
        }
        
        flash('Error uploading file.', 'error');
        return null;
    }
}