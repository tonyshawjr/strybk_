<?php
/**
 * Page Model
 * Handles database operations for book pages
 */

class Page {
    private Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    /**
     * Get all pages for a book
     */
    public function getBookPages(int $bookId): array {
        $sql = "SELECT * FROM pages WHERE book_id = ? ORDER BY order_index ASC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get a single page by ID
     */
    public function find(int $id): ?array {
        $sql = "SELECT * FROM pages WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Get a page by book and slug
     */
    public function findByBookAndSlug(int $bookId, string $slug): ?array {
        $sql = "SELECT * FROM pages WHERE book_id = ? AND slug = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId, $slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Create a new page
     */
    public function create(array $data): int {
        // Get the next order index
        $orderIndex = $this->getNextOrderIndex($data['book_id']);
        
        $sql = "INSERT INTO pages (book_id, title, slug, content, kind, order_index, word_count) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['book_id'],
            $data['title'],
            $data['slug'],
            $data['content'] ?? '',
            $data['kind'] ?? 'text',
            $orderIndex,
            $data['word_count'] ?? 0
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update a page
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE pages SET title = ?, content = ?, word_count = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['content'],
            $data['word_count'] ?? $this->calculateWordCount($data['content']),
            $id
        ]);
    }
    
    /**
     * Delete a page
     */
    public function delete(int $id): bool {
        // Get page info before deletion
        $page = $this->find($id);
        if (!$page) return false;
        
        // Delete the page
        $sql = "DELETE FROM pages WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$id]);
        
        // Reorder remaining pages
        if ($result) {
            $this->reorderAfterDeletion($page['book_id'], $page['order_index']);
        }
        
        return $result;
    }
    
    /**
     * Reorder pages
     */
    public function reorder(array $pageIds): bool {
        $sql = "UPDATE pages SET order_index = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        
        foreach ($pageIds as $index => $pageId) {
            $stmt->execute([$index, $pageId]);
        }
        
        return true;
    }
    
    /**
     * Get next order index for a book
     */
    private function getNextOrderIndex(int $bookId): int {
        $sql = "SELECT MAX(order_index) FROM pages WHERE book_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        $maxIndex = $stmt->fetchColumn();
        return ($maxIndex !== null) ? $maxIndex + 1 : 0;
    }
    
    /**
     * Reorder pages after deletion
     */
    private function reorderAfterDeletion(int $bookId, int $deletedIndex): void {
        $sql = "UPDATE pages SET order_index = order_index - 1 
                WHERE book_id = ? AND order_index > ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId, $deletedIndex]);
    }
    
    /**
     * Calculate word count from content
     */
    public function calculateWordCount(string $content): int {
        // Remove Markdown formatting for accurate count
        $plainText = strip_tags($content);
        $plainText = preg_replace('/[#*_\[\]()>`~-]+/', '', $plainText);
        return str_word_count($plainText);
    }
    
    /**
     * Generate unique slug for page
     */
    public function generateSlug(int $bookId, string $title): string {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        $baseSlug = $slug;
        $counter = 1;
        
        while ($this->findByBookAndSlug($bookId, $slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
}