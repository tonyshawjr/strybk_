# TASKS

## Hotfix
- [ ] N/A - New project

## Today (Phase 1 - Milestone 1: Setup)
- [x] Create project structure
- [x] Set up README with installation instructions
- [x] Create context documentation
- [x] Set up .gitignore
- [x] Create database schema SQL files
- [x] Build installer wizard (install/index.php)
- [x] Implement database connection class
- [x] Create basic auth system (login/logout)
- [x] Set up config.example.php
- [x] Write Playwright tests for installer
- [x] Initial git commit and push to main

## Next (Phase 1 - Milestone 4: Reorder & Navigation)
- [ ] Implement SortableJS for drag-drop page reordering
- [ ] Add visual reordering interface in book edit
- [ ] Create breadcrumb component for navigation
- [ ] Add keyboard shortcuts for reader navigation
## Backlog (Phase 1 - Remaining Milestones)

### Milestone 5: Polish
- [ ] Design minimal CSS system
- [ ] Implement typography styles
- [ ] Make responsive layouts
- [ ] Test on Hostinger Cloud

## Done
- [2025-09-25] Project initialization
  - Created folder structure
  - Set up README and documentation
  - Configured .gitignore
  - Established context files

- [2025-09-25] Phase 1 Milestone 2: Books - COMPLETE
  - Created Book model with full CRUD operations
  - Built BookController with all book management features
  - Created library view page (books/index.php)
  - Built "New Book" form with cover upload
  - Implemented book edit page with settings
  - Added book visibility toggle (public/private)
  - Created DashboardController and dashboard view
  - Created Page model for future page management
  - Added Playwright tests: books.spec.ts, auth.spec.ts, smoke.spec.ts

- [2025-09-25] Phase 1 Milestone 3: Pages - COMPLETE
  - Created PageController with full CRUD operations
  - Integrated SimpleMDE Markdown editor
  - Implemented Parsedown for Markdown rendering
  - Built auto-generated table of contents
  - Added real-time word count tracking
  - Created multiple page types (chapter, section, picture, divider)
  - Built ReadController for public book viewing
  - Created beautiful reader interface with TOC sidebar
  - Added prev/next navigation with keyboard support
  - Implemented reading progress indicator

## Links

- GitHub: <https://github.com/tonyshawjr/strybk_.git>
- Live Server: TBD (will be provided)
- Design Inspiration: <https://once.com/writebook>
