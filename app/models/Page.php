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
        return $this->db->select($sql, [$bookId]);
    }
    
    /**
     * Get a single page by ID
     */
    public function find(int $id): ?array {
        $sql = "SELECT * FROM pages WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }
    
    /**
     * Get a page by book and slug
     */
    public function findByBookAndSlug(int $bookId, string $slug): ?array {
        $sql = "SELECT * FROM pages WHERE book_id = ? AND slug = ?";
        return $this->db->selectOne($sql, [$bookId, $slug]);
    }
    
    /**
     * Create a new page
     */
    public function create(array $data): int {
        // Get the next order index
        $orderIndex = $this->getNextOrderIndex($data['book_id']);
        
        return $this->db->insert('pages', [
            'book_id' => $data['book_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'] ?? '',
            'kind' => $data['kind'] ?? 'text',
            'order_index' => $orderIndex,
            'word_count' => $data['word_count'] ?? 0
        ]);
    }
    
    /**
     * Update a page
     */
    public function update(int $id, array $data): bool {
        $wordCount = $data['word_count'] ?? $this->calculateWordCount($data['content']);
        
        return $this->db->update('pages', [
            'title' => $data['title'],
            'content' => $data['content'],
            'word_count' => $wordCount
        ], 'id = :id', ['id' => $id]) > 0;
    }
    
    /**
     * Delete a page
     */
    public function delete(int $id): bool {
        // Get page info before deletion
        $page = $this->find($id);
        if (!$page) return false;
        
        // Delete the page
        $result = $this->db->delete('pages', 'id = :id', ['id' => $id]) > 0;
        
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
        
        foreach ($pageIds as $index => $pageId) {
            $this->db->query($sql, [$index, $pageId]);
        }
        
        return true;
    }
    
    /**
     * Get next order index for a book
     */
    private function getNextOrderIndex(int $bookId): int {
        $sql = "SELECT MAX(order_index) as max_index FROM pages WHERE book_id = ?";
        $result = $this->db->selectOne($sql, [$bookId]);
        return ($result && $result['max_index'] !== null) ? $result['max_index'] + 1 : 0;
    }
    
    /**
     * Reorder pages after deletion
     */
    private function reorderAfterDeletion(int $bookId, int $deletedIndex): void {
        $sql = "UPDATE pages SET order_index = order_index - 1 
                WHERE book_id = ? AND order_index > ?";
        $this->db->query($sql, [$bookId, $deletedIndex]);
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