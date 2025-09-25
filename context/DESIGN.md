# DESIGN

## Architecture Overview

### Frontend
- **Technology**: Vanilla HTML/CSS/JavaScript
- **CSS Framework**: Custom minimal system
- **Editor**: SimpleMDE for Markdown editing
- **Drag & Drop**: SortableJS for page reordering
- **Typography**: Inter for UI, Georgia for reading

### Backend
- **Language**: PHP 8.2+
- **Pattern**: Simple MVC structure
- **Routing**: Front controller pattern (index.php)
- **Sessions**: PHP native sessions
- **Markdown**: Parsedown PHP library

### Database
- **System**: MySQL 5.7+ / MariaDB
- **Schema**: Normalized relational design
- **Migrations**: SQL files in install/

### Integrations
- **File Uploads**: PHP native handling
- **Image Processing**: Basic PHP GD library
- **No external APIs initially**

## Data Models

### Users Table
```sql
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(255) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    INDEX idx_email (email)
);
```

### Books Table
```sql
CREATE TABLE books (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) UNIQUE NOT NULL,
    subtitle VARCHAR(255),
    author VARCHAR(100),
    cover_path VARCHAR(500),
    is_public BOOLEAN DEFAULT FALSE,
    created_by INT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_slug (slug),
    INDEX idx_public (is_public),
    INDEX idx_user (created_by)
);
```

### Pages Table
```sql
CREATE TABLE pages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    book_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    slug VARCHAR(255) NOT NULL,
    content TEXT,
    kind ENUM('text', 'picture', 'section') DEFAULT 'text',
    order_index INT DEFAULT 0,
    word_count INT DEFAULT 0,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (book_id) REFERENCES books(id) ON DELETE CASCADE,
    UNIQUE KEY unique_book_slug (book_id, slug),
    INDEX idx_book_order (book_id, order_index)
);
```

## API Contracts

### Authentication
- `POST /auth/login` - User login
- `POST /auth/logout` - User logout
- `POST /auth/register` - Initial user registration (installer only)

### Books
- `GET /books` - List all books (filtered by visibility)
- `GET /books/{slug}` - View single book
- `POST /books` - Create new book
- `PUT /books/{id}` - Update book
- `DELETE /books/{id}` - Delete book

### Pages
- `GET /books/{book_id}/pages` - List pages in order
- `POST /books/{book_id}/pages` - Create new page
- `PUT /pages/{id}` - Update page content
- `DELETE /pages/{id}` - Delete page
- `POST /pages/reorder` - Update page ordering

## Component Design

### Key UI Components

#### Library View
- Grid layout of book covers
- Quick actions (edit, delete, visibility)
- New book button
- Search/filter controls

#### Book Editor
- Metadata form (title, author, etc.)
- Cover image upload
- Page list with reorder
- Add page buttons

#### Page Editor
- SimpleMDE Markdown editor
- Live preview toggle
- Save/cancel actions
- Word count display
- Page type selector

#### Reader View
- Clean typography layout
- Table of contents sidebar
- Previous/next navigation
- Progress indicator
- Full-screen toggle

## Testing Strategy

### Playwright E2E Tests
- **Tags**: @smoke, @critical, @slow, @quarantine
- **Traces**: On first retry only
- **Reports**: HTML report published in CI
- **Selectors**: Test IDs and accessible selectors

### Test Coverage
1. **Installation Flow** (@critical)
   - Database setup
   - User creation
   - Initial configuration

2. **Authentication** (@smoke)
   - Login/logout
   - Session persistence
   - Invalid credentials

3. **Book Management** (@smoke)
   - Create book
   - Edit metadata
   - Toggle visibility
   - Delete book

4. **Page Editing** (@critical)
   - Create pages
   - Edit content
   - Reorder pages
   - Delete pages

5. **Reading Experience** (@smoke)
   - Navigate pages
   - TOC functionality
   - Public/private access

## ADRs (Architecture Decision Records)

### Decision: Vanilla PHP over Framework
- **Context**: Need shared hosting compatibility
- **Options**: Laravel, Symfony, Vanilla PHP
- **Decision**: Vanilla PHP for maximum compatibility
- **Impact**: More manual work but guaranteed hosting support

### Decision: Markdown over WYSIWYG
- **Context**: Need simple, portable content format
- **Options**: TinyMCE, CKEditor, Markdown
- **Decision**: Markdown with SimpleMDE editor
- **Impact**: Clean content storage, easy export

### Decision: File-based uploads
- **Context**: Shared hosting limitations
- **Options**: Cloud storage, database, filesystem
- **Decision**: Local filesystem with size limits
- **Impact**: Simple implementation, hosting friendly

## Security Considerations

1. **Authentication**
   - Bcrypt password hashing
   - Session regeneration on login
   - Secure session cookies

2. **Input Validation**
   - Prepared statements for SQL
   - HTML purification for output
   - File type/size validation

3. **Authorization**
   - User can only edit own books
   - Public/private book access control
   - Admin-only installer access

## Performance Optimizations

1. **Database**
   - Proper indexes on foreign keys
   - Slug indexes for URL lookups
   - Pagination for large lists

2. **Frontend**
   - Minimal CSS/JS bundle
   - Lazy load images
   - Browser caching headers

3. **Backend**
   - Query result caching
   - Optimized file serving
   - Gzip compression