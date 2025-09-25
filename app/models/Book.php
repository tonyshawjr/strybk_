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
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    /**
     * Get a single book by ID
     */
    public function find(int $id): ?array {
        $sql = "SELECT * FROM books WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Get a book by slug
     */
    public function findBySlug(string $slug): ?array {
        $sql = "SELECT * FROM books WHERE slug = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$slug]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }
    
    /**
     * Create a new book
     */
    public function create(array $data): int {
        $sql = "INSERT INTO books (title, slug, subtitle, author, cover_path, is_public, created_by) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['slug'],
            $data['subtitle'] ?? null,
            $data['author'] ?? null,
            $data['cover_path'] ?? null,
            $data['is_public'] ?? false,
            $data['created_by']
        ]);
        return $this->db->lastInsertId();
    }
    
    /**
     * Update a book
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE books SET title = ?, subtitle = ?, author = ?, is_public = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([
            $data['title'],
            $data['subtitle'] ?? null,
            $data['author'] ?? null,
            $data['is_public'] ?? false,
            $id
        ]);
    }
    
    /**
     * Update book cover
     */
    public function updateCover(int $id, string $coverPath): bool {
        $sql = "UPDATE books SET cover_path = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$coverPath, $id]);
    }
    
    /**
     * Toggle book visibility
     */
    public function toggleVisibility(int $id): bool {
        $sql = "UPDATE books SET is_public = NOT is_public WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Delete a book
     */
    public function delete(int $id): bool {
        $sql = "DELETE FROM books WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    /**
     * Check if user owns the book
     */
    public function isOwner(int $bookId, int $userId): bool {
        $sql = "SELECT COUNT(*) FROM books WHERE id = ? AND created_by = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId, $userId]);
        return $stmt->fetchColumn() > 0;
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
        $sql = "SELECT COUNT(*) FROM pages WHERE book_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        return $stmt->fetchColumn();
    }
    
    /**
     * Get total word count for a book
     */
    public function getWordCount(int $bookId): int {
        $sql = "SELECT SUM(word_count) FROM pages WHERE book_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$bookId]);
        return $stmt->fetchColumn() ?: 0;
    }
}