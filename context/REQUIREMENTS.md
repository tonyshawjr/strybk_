# REQUIREMENTS

## Vision
Build a minimalist online book publishing tool inspired by 37signals' Writebook, implemented in vanilla PHP + MySQL for shared hosting compatibility. Focus on simplicity, typography, and distraction-free writing.

## Functional Requirements

### Phase 1 - MVP (Broken into Milestones)

#### Milestone 1: Setup
- Installer wizard for database setup
- First user creation during install
- Basic authentication (login/logout)
- Password hashing with PHP's password_hash

#### Milestone 2: Books
- Library view to list all books
- Create new book with title, subtitle, author
- Upload book cover image
- Toggle book visibility (public/private)

#### Milestone 3: Pages
- Add/edit text pages with Markdown
- Markdown rendering for reading view
- Auto-generated table of contents
- Word count tracking

#### Milestone 4: Reorder & Navigation
- Drag-and-drop page reordering
- Previous/next navigation in books
- Breadcrumb navigation

#### Milestone 5: Polish
- Minimal CSS design system
- Typography-focused layout
- Responsive design
- Deployment testing on Hostinger Cloud

### Phase 2 - Media & Polish
- Picture pages with captions
- Section divider pages
- Image uploads within text pages
- Full-screen reading mode
- Keyboard navigation (j/k for prev/next)

### Phase 3 - Extras & Expansion
- Page version history with diffs
- Export book to Markdown/HTML
- Book search functionality
- Site-wide theming options

### Phase 4 - Future Collaboration (Optional)
- User roles (owner, writer, reader)
- Email invitations
- Private books for members only
- Access control per book

## Non-Functional Requirements

### Performance
- Page load under 2 seconds on shared hosting
- Lazy load images
- Efficient database queries
- Minimal JavaScript dependencies

### Security and Compliance
- Secure password storage (bcrypt)
- SQL injection prevention
- XSS protection
- CSRF tokens for forms
- File upload validation

### Privacy and Data Policy
- User data stored locally only
- No third-party tracking
- Export functionality for data portability
- Clear deletion options

### Observability and Metrics
- Basic error logging
- Word count statistics
- Book view analytics (optional)

### Testing
- Playwright E2E tests for critical flows
- Smoke suite for quick validation
- Installation wizard testing
- Cross-browser compatibility

## Out of Scope
- PDF/ePub export (future consideration)
- Real-time collaboration
- Plugin marketplace
- Complex WYSIWYG editor
- Mobile apps
- API access (initially)

## Success Criteria
- Smoke suite @smoke green on PRs
- Full test suite green on main
- Works on Hostinger Cloud shared hosting
- Installation completes in under 5 minutes
- Can create, edit, and publish a book
- Clean typography and reading experience
- Mobile responsive design