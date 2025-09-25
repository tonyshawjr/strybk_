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
        // Get the next order_index if not provided
        $orderIndex = isset($data['position']) ? $data['position'] - 1 : $this->getNextOrderIndex($data['book_id']);
        
        // Map our kinds to database kinds
        $kind = $this->mapKindToDatabase($data['kind'] ?? 'text');
        
        return $this->db->insert('pages', [
            'book_id' => $data['book_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'] ?? '',
            'kind' => $kind,
            'order_index' => $orderIndex,
            'word_count' => $data['word_count'] ?? 0
        ]);
    }
    
    /**
     * Update a page
     */
    public function update(int $id, array $data): bool {
        $updateData = [];
        
        if (isset($data['title'])) $updateData['title'] = $data['title'];
        if (isset($data['content'])) $updateData['content'] = $data['content'];
        if (isset($data['kind'])) $updateData['kind'] = $this->mapKindToDatabase($data['kind']);
        if (isset($data['position'])) $updateData['order_index'] = $data['position'] - 1;
        if (isset($data['word_count'])) $updateData['word_count'] = $data['word_count'];
        
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
            $this->reorderAfterDeletion($page['book_id'], $page['order_index']);
        }
        
        return $result;
    }
    
    /**
     * Update page position
     */
    public function updatePosition(int $id, int $position): bool {
        return $this->db->update('pages', [
            'order_index' => $position - 1
        ], 'id = :id', ['id' => $id]) > 0;
    }
    
    /**
     * Get next order_index for a book
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
    
    /**
     * Map our kind values to database enum values
     */
    private function mapKindToDatabase(string $kind): string {
        $mapping = [
            'chapter' => 'text',
            'section' => 'section',
            'picture' => 'picture',
            'divider' => 'text'  // Store dividers as text with empty content
        ];
        
        return $mapping[$kind] ?? 'text';
    }
    
    /**
     * Map database kind values to our kind values
     */
    public function mapKindFromDatabase(string $dbKind): string {
        $mapping = [
            'text' => 'chapter',
            'section' => 'section',
            'picture' => 'picture'
        ];
        
        return $mapping[$dbKind] ?? 'chapter';
    }
}