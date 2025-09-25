# Changelog

All notable changes to this project are tracked here.

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