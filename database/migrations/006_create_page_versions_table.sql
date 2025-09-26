-- Create page_versions table for tracking version history
CREATE TABLE IF NOT EXISTS page_versions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    page_id INT NOT NULL,
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    version_number INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content LONGTEXT,
    word_count INT DEFAULT 0,
    kind ENUM('text', 'section', 'picture') DEFAULT 'text',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    
    -- Foreign keys
    FOREIGN KEY (page_id) REFERENCES pages(id) ON DELETE CASCADE,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    
    -- Indexes for performance
    INDEX idx_page_versions (page_id, version_number DESC),
    INDEX idx_page_created (page_id, created_at DESC),
    UNIQUE KEY unique_page_version (page_id, version_number)
);

-- Add current_version column to pages table
ALTER TABLE pages 
ADD COLUMN current_version INT DEFAULT 1 AFTER word_count;

-- Add version_count column to pages table for quick access
ALTER TABLE pages 
ADD COLUMN version_count INT DEFAULT 0 AFTER current_version;