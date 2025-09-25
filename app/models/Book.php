<?php
/**
 * Book Model
 * Handles database operations for books
 */

class Book {
    private Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    /**
     * Get all books for a specific user
     */
    public function getUserBooks(int $userId): array {
        $sql = "SELECT * FROM books WHERE created_by = ? ORDER BY updated_at DESC";
        return $this->db->select($sql, [$userId]);
    }
    
    /**
     * Get a single book by ID
     */
    public function find(int $id): ?array {
        $sql = "SELECT * FROM books WHERE id = ?";
        return $this->db->selectOne($sql, [$id]);
    }
    
    /**
     * Get a book by slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM books WHERE slug = ?";
        return $this->db->selectOne($sql, [$slug]);
    }
    
    /**
     * Create a new book
     */
    public function create(array $data): int {
        return $this->db->insert('books', [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'subtitle' => $data['subtitle'] ?? null,
            'author' => $data['author'] ?? null,
            'cover_path' => $data['cover_path'] ?? null,
            'is_public' => $data['is_public'] ?? 0,
            'created_by' => $data['created_by']
        ]);
    }
    
    /**
     * Update a book
     */
    public function update(int $id, array $data): bool {
        return $this->db->update('books', [
            'title' => $data['title'],
            'subtitle' => $data['subtitle'] ?? null,
            'author' => $data['author'] ?? null,
            'is_public' => $data['is_public'] ?? 0
        ], ['id' => $id]) > 0;
    }
    
    /**
     * Update book cover
     */
    public function updateCover(int $id, string $coverPath): bool {
        return $this->db->update('books', [
            'cover_path' => $coverPath
        ], ['id' => $id]) > 0;
    }
    
    /**
     * Toggle book visibility
     */
    public function toggleVisibility(int $id): bool {
        $sql = "UPDATE books SET is_public = NOT is_public WHERE id = ?";
        $stmt = $this->db->query($sql, [$id]);
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Delete a book
     */
    public function delete(int $id): bool {
        return $this->db->delete('books', ['id' => $id]) > 0;
    }
    
    /**
     * Check if user owns the book
     */
    public function isOwner(int $bookId, int $userId): bool {
        $sql = "SELECT COUNT(*) as count FROM books WHERE id = ? AND created_by = ?";
        $result = $this->db->selectOne($sql, [$bookId, $userId]);
        return $result && $result['count'] > 0;
    }
    
    /**
     * Generate unique slug from title
     */
    public function generateSlug(string $title): string {
        $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $title), '-'));
        $baseSlug = $slug;
        $counter = 1;
        
        while ($this->findBySlug($slug)) {
            $slug = $baseSlug . '-' . $counter;
            $counter++;
        }
        
        return $slug;
    }
    
    /**
     * Get page count for a book
     */
    public function getPageCount(int $bookId): int {
        $sql = "SELECT COUNT(*) as count FROM pages WHERE book_id = ?";
        $result = $this->db->selectOne($sql, [$bookId]);
        return $result ? (int)$result['count'] : 0;
    }
    
    /**
     * Get total word count for a book
     */
    public function getWordCount(int $bookId): int {
        $sql = "SELECT COALESCE(SUM(word_count), 0) as total FROM pages WHERE book_id = ?";
        $result = $this->db->selectOne($sql, [$bookId]);
        return $result ? (int)$result['total'] : 0;
    }
}