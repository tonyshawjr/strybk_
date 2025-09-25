# PLAN - Strybk_ Development Roadmap

Purpose: High-level roadmap linking to detailed requirements, design decisions, and current tasks.

## Project Overview

**Goal**: Build a minimalist book publishing tool that works on shared hosting (PHP/MySQL).

**Timeline**: 4 phases, with Phase 1 broken into 5 milestones for rapid iteration.

## Phases

### Phase 1 - MVP (Current Focus)
Delivering core functionality in small, testable chunks.

#### Milestone 1: Setup ← **CURRENT**
- Database installer wizard
- User authentication
- Basic project structure
- [See current tasks](./TASKS.md#today-phase-1---milestone-1-setup)

#### Milestone 2: Books
- Library view
- Book CRUD operations
- Cover uploads
- Visibility controls
- [See planned tasks](./TASKS.md#next-phase-1---milestone-2-books)

#### Milestone 3: Pages
- Markdown editor integration
- Page creation/editing
- Auto-generated TOC
- Word counting

#### Milestone 4: Reorder & Navigation
- Drag-drop page ordering
- Reader navigation
- Breadcrumb trails

#### Milestone 5: Polish
- CSS design system
- Typography refinement
- Mobile responsiveness
- Hostinger deployment

### Phase 2 - Media & Polish
- Picture pages with captions
- Section dividers
- In-text image uploads
- Full-screen reading mode
- Keyboard shortcuts

### Phase 3 - Extras & Expansion
- Version history
- Export functionality (Markdown/HTML)
- Search capabilities
- Theming options

### Phase 4 - Future Collaboration (Optional)
- Multi-user support
- Role-based permissions
- Private book sharing
- Team invitations

## Metrics

### Success Metrics
- **Phase 1**: Working on shared hosting, basic book creation/publishing
- **Phase 2**: Rich media support, polished reading experience
- **Phase 3**: Power user features, data portability
- **Phase 4**: Team collaboration capabilities

### Quality Gates
- All PRs must pass @smoke tests
- Main branch requires full test suite green
- Zero @critical test failures in production
- Installation completes < 5 minutes
- Page loads < 2 seconds on shared hosting

## Technical Decisions

Key architectural choices documented in [DESIGN.md](./DESIGN.md#adrs-architecture-decision-records):
- Vanilla PHP for hosting compatibility
- Markdown over WYSIWYG for portability
- File-based uploads for simplicity
- SimpleMDE for editor experience

## Risk Mitigation

1. **Scope Creep**: Strict phase boundaries, milestone-based delivery
2. **Hosting Limits**: File size validation, efficient queries
3. **Complexity**: Start simple, iterate based on feedback
4. **Testing**: Playwright from day one, smoke tests on every PR

## Resources

- [Requirements](./REQUIREMENTS.md) - What we're building
- [Design](./DESIGN.md) - How we're building it
- [Tasks](./TASKS.md) - Current work items
- [Changelog](../CHANGELOG.md) - Version history

## Development Workflow

1. Complete current milestone
2. Run test suite
3. Push to GitHub
4. Deploy to live server
5. Validate on Hostinger
6. Update version and changelog
7. Move to next milestone

## Contact

- **Project Lead**: Tony Shaw
- **Repository**: https://github.com/tonyshawjr/strybk_.git
- **Live Demo**: TBD