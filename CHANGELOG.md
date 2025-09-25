# Changelog

All notable changes to this project are tracked here.

## [0.3.0] - 2025-09-25
### Added
- Complete page management system (Phase 1 Milestone 3)
- PageController for full page CRUD operations
- Page editor with SimpleMDE Markdown editor integration
- Markdown rendering with Parsedown library
- Auto-generated table of contents (TOC)
- Real-time word count and character count tracking
- Support for multiple page types:
  - Chapter pages with full Markdown content
  - Section pages for sub-chapters
  - Picture pages with image upload support
  - Divider pages for visual breaks
- ReadController for public book reading experience
- Beautiful reader interface with:
  - Collapsible table of contents sidebar
  - Reading progress indicator
  - Keyboard navigation (arrow keys)
  - Previous/Next page navigation
  - Responsive design for mobile reading
- Page reordering functionality
- Views:
  - pages/create.php - New page form with SimpleMDE
  - pages/edit.php - Edit page with live preview
  - read/show.php - Book reader interface
  - read/empty.php - Empty book state
  - errors/403.php - Access denied page

### Changed
- Updated routes to include page management endpoints
- Page model now supports multiple content types
- Book edit view now shows page management interface

### Fixed
- Fixed undefined config['base_url'] reference in book edit view
- Updated Playwright test selectors to avoid ambiguity

## [0.2.1] - 2025-09-25
### Fixed
- Removed undefined config['base_url'] reference in book edit view
- Fixed Playwright test selectors to use ID selectors instead of label selectors
- Updated visibility toggle test logic to check for button existence first

## [0.2.0] - 2025-09-25
### Added
- Complete book management system (Phase 1 Milestone 2)
- Book model with CRUD operations and slug generation
- BookController handling all book operations
- Library view showing all user's books with stats
- Create new book form with cover image upload
- Edit book page with settings and page management
- Book visibility toggle (public/private)
- Dashboard with statistics and recent books
- Page model for future page management
- DashboardController for main dashboard
- Playwright E2E tests:
  - books.spec.ts - Book management tests
  - auth.spec.ts - Authentication tests  
  - smoke.spec.ts - Basic smoke tests
- Support for book cover images (JPG, PNG, WebP)
- Word count and page count tracking

### Changed
- Routes now include full book management endpoints
- Database schema utilized for books and pages tables

### Fixed
- N/A

## [0.1.0] - 2025-09-25
### Added
- Initial project setup with folder structure
- README.md with project overview and installation instructions
- Context folder with requirements, design, tasks, and plan documents
- Playwright test structure for E2E testing
- AgentOS configuration for standards and workflows
- Database schema design for users, books, and pages
- Brand guidelines and color scheme

### Changed
- N/A

### Fixed
- N/A