<?php
/**
 * Read Controller
 * Handles public book reading experience
 */

require_once __DIR__ . '/../libs/Parsedown.php';

class ReadController {
    private Database $db;
    private Auth $auth;
    private Book $bookModel;
    private Page $pageModel;
    private array $config;
    private Parsedown $parsedown;
    
    public function __construct(Database $db, Auth $auth, array $config) {
        $this->db = $db;
        $this->auth = $auth;
        $this->config = $config;
        $this->bookModel = new Book($db);
        $this->pageModel = new Page($db);
        $this->parsedown = new Parsedown();
        $this->parsedown->setSafeMode(true); // Prevent XSS
    }
    
    /**
     * Display book reader
     */
    public function show(string $bookSlug, ?string $pageSlug = null): void {
        // Get book by slug
        $book = $this->bookModel->findBySlug($bookSlug);
        
        if (!$book) {
            http_response_code(404);
            view('errors/404', [
                'title' => 'Book Not Found'
            ]);
            return;
        }
        
        // Check if book is public or user owns it
        $userId = $this->auth->check() ? $this->auth->user()['id'] : null;
        $canView = $book['is_public'] || ($userId && $book['created_by'] == $userId);
        
        if (!$canView) {
            http_response_code(403);
            view('errors/403', [
                'title' => 'Access Denied'
            ]);
            return;
        }
        
        // Get all pages for the book
        $pages = $this->pageModel->getBookPages($book['id']);
        
        if (empty($pages)) {
            view('read/empty', [
                'title' => $book['title'],
                'book' => $book,
                'auth' => $this->auth
            ]);
            return;
        }
        
        // Find current page
        $currentPage = null;
        if ($pageSlug) {
            foreach ($pages as $page) {
                if ($page['slug'] === $pageSlug) {
                    $currentPage = $page;
                    break;
                }
            }
        }
        
        // If no page specified or page not found, show first page
        if (!$currentPage) {
            $currentPage = $pages[0];
        }
        
        // Process page content based on type
        if (in_array($currentPage['kind'], ['chapter', 'section'])) {
            $currentPage['rendered_content'] = $this->parsedown->text($currentPage['content']);
        }
        
        // Generate table of contents
        $toc = $this->generateTOC($pages, $currentPage['id']);
        
        // Find prev/next pages
        $prevPage = null;
        $nextPage = null;
        foreach ($pages as $index => $page) {
            if ($page['id'] == $currentPage['id']) {
                if ($index > 0) {
                    $prevPage = $pages[$index - 1];
                }
                if ($index < count($pages) - 1) {
                    $nextPage = $pages[$index + 1];
                }
                break;
            }
        }
        
        // Calculate reading progress
        $currentIndex = array_search($currentPage['id'], array_column($pages, 'id'));
        $progress = round((($currentIndex + 1) / count($pages)) * 100);
        
        // Calculate total word count and reading time
        $totalWords = 0;
        foreach ($pages as $page) {
            if (in_array($page['kind'], ['chapter', 'section'])) {
                $totalWords += $page['word_count'];
            }
        }
        $readingTime = ceil($totalWords / 200); // Average reading speed: 200 words/minute
        
        view('read/show', [
            'title' => $book['title'] . ' - ' . $currentPage['title'],
            'auth' => $this->auth,
            'book' => $book,
            'pages' => $pages,
            'currentPage' => $currentPage,
            'prevPage' => $prevPage,
            'nextPage' => $nextPage,
            'toc' => $toc,
            'progress' => $progress,
            'totalWords' => $totalWords,
            'readingTime' => $readingTime
        ]);
    }
    
    /**
     * Generate table of contents
     */
    private function generateTOC(array $pages, int $currentPageId): array {
        $toc = [];
        $chapterNumber = 0;
        $sectionNumber = 0;
        
        foreach ($pages as $page) {
            if ($page['kind'] === 'chapter') {
                $chapterNumber++;
                $sectionNumber = 0;
                $toc[] = [
                    'id' => $page['id'],
                    'slug' => $page['slug'],
                    'title' => $page['title'],
                    'kind' => 'chapter',
                    'number' => $chapterNumber,
                    'is_current' => $page['id'] == $currentPageId,
                    'word_count' => $page['word_count']
                ];
            } elseif ($page['kind'] === 'section') {
                $sectionNumber++;
                $toc[] = [
                    'id' => $page['id'],
                    'slug' => $page['slug'],
                    'title' => $page['title'],
                    'kind' => 'section',
                    'number' => $chapterNumber . '.' . $sectionNumber,
                    'is_current' => $page['id'] == $currentPageId,
                    'word_count' => $page['word_count']
                ];
            }
        }
        
        return $toc;
    }
}