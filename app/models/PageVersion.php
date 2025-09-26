<?php
/**
 * PageVersion Model
 * Handles version history for pages
 */

class PageVersion {
    private Database $db;
    
    public function __construct(Database $db) {
        $this->db = $db;
    }
    
    /**
     * Create a new version when a page is saved
     */
    public function createVersion(int $pageId, array $data): int {
        // Get the next version number
        $versionNumber = $this->getNextVersionNumber($pageId);
        
        $sql = "INSERT INTO page_versions (
                    page_id, book_id, user_id, version_number,
                    title, content, word_count, kind
                ) VALUES (
                    :page_id, :book_id, :user_id, :version_number,
                    :title, :content, :word_count, :kind
                )";
        
        $params = [
            'page_id' => $pageId,
            'book_id' => $data['book_id'],
            'user_id' => $data['user_id'],
            'version_number' => $versionNumber,
            'title' => $data['title'],
            'content' => $data['content'] ?? '',
            'word_count' => $data['word_count'] ?? 0,
            'kind' => $data['kind'] ?? 'text'
        ];
        
        $versionId = $this->db->insert('page_versions', $params);
        
        // Update the page's current version and version count
        $this->db->update('pages', [
            'current_version' => $versionNumber,
            'version_count' => $versionNumber
        ], 'id = :id', ['id' => $pageId]);
        
        // Prune old versions (keep last 5)
        $this->pruneVersions($pageId, 5);
        
        return $versionId;
    }
    
    /**
     * Get all versions for a page
     */
    public function getPageVersions(int $pageId): array {
        $sql = "SELECT pv.*, 
                       u.name as author_name,
                       DATE_FORMAT(pv.created_at, '%Y-%m-%d %H:%i:%s') as created_at_formatted
                FROM page_versions pv
                LEFT JOIN users u ON pv.user_id = u.id
                WHERE pv.page_id = ?
                ORDER BY pv.version_number DESC";
        
        $results = $this->db->select($sql, [$pageId]);
        
        // Ensure created_at is properly formatted
        foreach ($results as &$result) {
            if (!empty($result['created_at_formatted'])) {
                $result['created_at'] = $result['created_at_formatted'];
            }
        }
        
        return $results;
    }
    
    /**
     * Get a specific version
     */
    public function getVersion(int $pageId, int $versionNumber): ?array {
        $sql = "SELECT pv.*, 
                       u.name as author_name,
                       DATE_FORMAT(pv.created_at, '%Y-%m-%d %H:%i:%s') as created_at_formatted,
                       pv.word_count as word_count
                FROM page_versions pv
                LEFT JOIN users u ON pv.user_id = u.id
                WHERE pv.page_id = ? AND pv.version_number = ?";
        
        $result = $this->db->selectOne($sql, [$pageId, $versionNumber]);
        
        // Ensure created_at is properly formatted
        if ($result && !empty($result['created_at_formatted'])) {
            $result['created_at'] = $result['created_at_formatted'];
        }
        
        return $result;
    }
    
    /**
     * Get the latest version
     */
    public function getLatestVersion(int $pageId): ?array {
        $sql = "SELECT pv.*, u.name as author_name
                FROM page_versions pv
                LEFT JOIN users u ON pv.user_id = u.id
                WHERE pv.page_id = ?
                ORDER BY pv.version_number DESC
                LIMIT 1";
        
        return $this->db->selectOne($sql, [$pageId]);
    }
    
    /**
     * Restore a specific version
     */
    public function restoreVersion(int $pageId, int $versionNumber, int $userId): bool {
        // Get the version to restore
        $version = $this->getVersion($pageId, $versionNumber);
        if (!$version) {
            return false;
        }
        
        // Create a new version with the restored content
        $this->createVersion($pageId, [
            'book_id' => $version['book_id'],
            'user_id' => $userId,
            'title' => $version['title'],
            'content' => $version['content'],
            'word_count' => $version['word_count'],
            'kind' => $version['kind']
        ]);
        
        // Update the current page with the restored content
        $this->db->update('pages', [
            'title' => $version['title'],
            'content' => $version['content'],
            'word_count' => $version['word_count'],
            'kind' => $version['kind'],
            'updated_at' => date('Y-m-d H:i:s')
        ], 'id = :id', ['id' => $pageId]);
        
        return true;
    }
    
    /**
     * Compare two versions
     */
    public function compareVersions(int $pageId, int $version1, int $version2): array {
        $v1 = $this->getVersion($pageId, $version1);
        $v2 = $this->getVersion($pageId, $version2);
        
        if (!$v1 || !$v2) {
            return [];
        }
        
        return [
            'version1' => $v1,
            'version2' => $v2,
            'title_changed' => $v1['title'] !== $v2['title'],
            'content_changed' => $v1['content'] !== $v2['content'],
            'word_count_diff' => $v2['word_count'] - $v1['word_count']
        ];
    }
    
    /**
     * Get the next version number for a page
     */
    private function getNextVersionNumber(int $pageId): int {
        $sql = "SELECT MAX(version_number) as max_version 
                FROM page_versions 
                WHERE page_id = ?";
        
        $result = $this->db->selectOne($sql, [$pageId]);
        return ($result && $result['max_version']) ? $result['max_version'] + 1 : 1;
    }
    
    /**
     * Delete old versions (keep last N versions)
     */
    public function pruneVersions(int $pageId, int $keepCount = 5): int {
        // Get the version numbers to keep
        $sql = "SELECT version_number 
                FROM page_versions 
                WHERE page_id = ?
                ORDER BY version_number DESC
                LIMIT ?";
        
        $keepVersions = $this->db->select($sql, [$pageId, $keepCount]);
        
        if (empty($keepVersions)) {
            return 0;
        }
        
        $keepNumbers = array_column($keepVersions, 'version_number');
        $placeholders = implode(',', array_fill(0, count($keepNumbers), '?'));
        
        $sql = "DELETE FROM page_versions 
                WHERE page_id = ? 
                AND version_number NOT IN ($placeholders)";
        
        $params = array_merge([$pageId], $keepNumbers);
        return $this->db->query($sql, $params);
    }
    
    /**
     * Get version statistics for a page
     */
    public function getVersionStats(int $pageId): array {
        $sql = "SELECT 
                    COUNT(*) as total_versions,
                    MIN(created_at) as first_version_date,
                    MAX(created_at) as last_version_date,
                    COUNT(DISTINCT user_id) as unique_authors
                FROM page_versions
                WHERE page_id = ?";
        
        $stats = $this->db->selectOne($sql, [$pageId]) ?? [];
        
        // Get top contributors
        $sql = "SELECT u.name, COUNT(*) as version_count
                FROM page_versions pv
                JOIN users u ON pv.user_id = u.id
                WHERE pv.page_id = ?
                GROUP BY pv.user_id
                ORDER BY version_count DESC
                LIMIT 5";
        
        $stats['top_contributors'] = $this->db->select($sql, [$pageId]);
        
        return $stats;
    }
}