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
        $sql = "SELECT * FROM pages WHERE book_id = ? ORDER BY position ASC";
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
        // Get the next position if not provided
        $position = $data['position'] ?? $this->getNextPosition($data['book_id']);
        
        return $this->db->insert('pages', [
            'book_id' => $data['book_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'] ?? '',
            'kind' => $data['kind'] ?? 'chapter',
            'position' => $position,
            'word_count' => $data['word_count'] ?? 0,
            'image_path' => $data['image_path'] ?? null
        ]);
    }
    
    /**
     * Update a page
     */
    public function update(int $id, array $data): bool {
        $updateData = [];
        
        if (isset($data['title'])) $updateData['title'] = $data['title'];
        if (isset($data['content'])) $updateData['content'] = $data['content'];
        if (isset($data['kind'])) $updateData['kind'] = $data['kind'];
        if (isset($data['position'])) $updateData['position'] = $data['position'];
        if (isset($data['word_count'])) $updateData['word_count'] = $data['word_count'];
        if (isset($data['image_path'])) $updateData['image_path'] = $data['image_path'];
        
        if (empty($updateData)) return false;
        
        return $this->db->update('pages', $updateData, 'id = :id', ['id' => $id]) > 0;
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
            $this->reorderAfterDeletion($page['book_id'], $page['position']);
        }
        
        return $result;
    }
    
    /**
     * Update page position
     */
    public function updatePosition(int $id, int $position): bool {
        return $this->db->update('pages', [
            'position' => $position
        ], 'id = :id', ['id' => $id]) > 0;
    }
    
    /**
     * Get next position for a book
     */
    private function getNextPosition(int $bookId): int {
        $sql = "SELECT MAX(position) as max_pos FROM pages WHERE book_id = ?";
        $result = $this->db->selectOne($sql, [$bookId]);
        return ($result && $result['max_pos'] !== null) ? $result['max_pos'] + 1 : 1;
    }
    
    /**
     * Reorder pages after deletion
     */
    private function reorderAfterDeletion(int $bookId, int $deletedPosition): void {
        $sql = "UPDATE pages SET position = position - 1 
                WHERE book_id = ? AND position > ?";
        $this->db->query($sql, [$bookId, $deletedPosition]);
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
    public function generateSlug(string $title, int $bookId): string {
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