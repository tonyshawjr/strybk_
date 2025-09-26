# Changelog

All notable changes to this project are tracked here.

## [0.5.1] - 2025-09-26
### Fixed
- Version history restore functionality now working correctly
- Restore button click handlers properly attached with global scope
- Updated diff view colors to black/white/gray scheme:
  - Removed text: light gray background with dark gray text
  - Added text: black background with white text
- Word-level diff algorithm showing only actual changes, not entire blocks
- Added debugging for restore operations

## [0.5.0] - 2025-09-26
### Added
- Complete design system and polish (Phase 1 Milestone 5)
- Comprehensive CSS design system (/public/css/app.css):
  - CSS variables for colors, typography, spacing, and shadows
  - Consistent design tokens across the application
  - Typography system with font scales
  - Grid and flexbox utilities
  - Form components and styling
  - Button variants and states
  - Alert and card components
  - Animation utilities
- Beautiful homepage with hero section and features
- HomeController for handling public pages
- Responsive navigation bar with mobile support
- Enhanced login page with design system integration
- Print styles for better document printing
- Accessibility improvements with focus states
- Smooth scroll for anchor links

### Changed
- Updated all views to use centralized CSS design system
- Replaced inline styles with CSS classes
- Improved responsive layouts across all pages
- Enhanced visual consistency throughout the application
- Updated flash messages to use alert components

### Fixed
- Cleaned up duplicate CSS styles in login page
- Improved mobile responsiveness for navigation

## [0.4.0] - 2025-09-26
### Added
- Complete page reordering and navigation system (Phase 1 Milestone 4)
- SortableJS integration for drag-and-drop page reordering
- Visual reordering interface with drag handles in book edit
- AJAX-based page order updates with real-time feedback
- Comprehensive breadcrumb navigation across all pages:
  - pages/create.php - Dashboard > Books > Book > New Page
  - pages/edit.php - Dashboard > Books > Book > Page Title
  - read/show.php - Library > Book > Current Page
- Enhanced keyboard shortcuts in reader:
  - A/D keys for previous/next navigation (in addition to arrow keys)
  - T to toggle table of contents
  - ESC to hide table of contents
  - H/? to show keyboard shortcuts help
- Keyboard shortcuts help modal with styled overlay
- Improved page reordering with position/order_index mapping

### Changed
- PageController reorder method now properly handles type conversion
- Reader interface keyboard navigation enhanced with multiple key options
- Added CSS styles for keyboard help modal

### Fixed
- Fixed database column mapping for page ordering (position vs order_index)
- Added missing header content-type for JSON responses in reorder endpoint

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