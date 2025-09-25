-- Migration: Update pages table for enhanced page types
-- Date: 2025-09-25

-- Update the kind enum to support our page types
ALTER TABLE pages 
MODIFY COLUMN kind ENUM('text', 'picture', 'section', 'chapter', 'divider') DEFAULT 'text';

-- Add image_path column for picture pages (if not exists)
ALTER TABLE pages 
ADD COLUMN IF NOT EXISTS image_path VARCHAR(500) NULL AFTER word_count;

-- Update existing 'text' pages to 'chapter' for clarity
UPDATE pages SET kind = 'chapter' WHERE kind = 'text';

-- Add position column as alias for order_index (for compatibility)
-- Note: We'll keep using order_index internally but map it in the application