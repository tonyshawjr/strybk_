# Project Requirements Document (PRD)

## Project Title
**Strybk_**

## Overview
We are building a **minimalist online book publishing tool** inspired by 37signals’ Writebook. Unlike the original (Rails + Docker), this will be implemented in **vanilla PHP + MySQL** to run on shared hosts (e.g., Hostinger Cloud, no terminal). The goal is to replicate the simplicity and typography-first design of Writebook while keeping development phases granular, allowing us to deliver working software quickly and iterate efficiently.

## Goals
- Provide a clean, distraction-free tool for creating and publishing online books.
- Support multiple books per account.
- Focus on simplicity and usability first, expand features gradually.
- Ensure compatibility with low-complexity hosting (PHP + MySQL only).

## Non-Goals (initially)
- PDF/ePub export (future consideration).
- Multi-user collaboration (pushed to much later phase).
- Rich plugins or marketplace.
- Heavy WYSIWYG complexity.

---

## Core Features (End Vision)
- **Books**: title, subtitle, cover, author, visibility toggle.
- **Pages**: text (Markdown), picture, or section pages.
- **Table of Contents (TOC)** with drag-and-drop ordering.
- **Simple Editor Toolbar**: bold, italic, quote, code, list, link, image, save.
- **Library View**: all books with covers.
- **Publishing**: toggle book visibility (draft/private/public).
- **Reader View**: clean typography, prev/next navigation.
- **Installer**: no-terminal setup wizard.

---

## Technical Constraints
- **Backend**: PHP 8.2+
- **Database**: MySQL 5.7/8.0 (or MariaDB on shared hosting)
- **Frontend**: HTML, CSS (custom system, minimal), JS (SortableJS, SimpleMDE for editor)
- **Markdown Engine**: Parsedown (PHP)
- **Auth**: PHP sessions + password hashing (`password_hash`)
- **Hosting**: Shared hosting compatible (Hostinger Cloud, cPanel environments)

---

## Database Schema (Initial)

**Users**
- id (PK)
- name
- email
- password_hash
- created_at

**Books**
- id (PK)
- title
- slug (unique)
- subtitle
- author
- cover_path
- is_public (bool)
- created_by (FK → users.id)
- created_at
- updated_at

**Pages**
- id (PK)
- book_id (FK → books.id)
- title
- slug (unique per book)
- content (Markdown)
- kind (enum: text, picture, section)
- order_index (int)
- word_count
- created_at
- updated_at

---

## File Structure
```
/public
  index.php
  assets/
  uploads/covers/
  uploads/pages/
/app
  config.php
  db.php
  helpers.php
  auth.php
  routes.php
  controllers/
  models/
  views/
/storage
/install
```

---

## Brand & Design

### Typography
- **UI**: Inter, sans-serif stack
- **Reading**: Georgia, serif stack

### Color Scheme (Strybk_)
- **Primary Purple**: #6C4AB6 (branding, accents, buttons)
- **Deep Indigo**: #2E1A47 (text, headings, ghost outline)
- **Lime Green**: #A8FF60 (highlights, secondary accents, hover states)
- **Soft Gray**: #F6F6F6 (background cards, inputs)
- **Charcoal**: #222222 (body text)
- **White**: #FFFFFF (main background)

Use purple as the anchor brand color, lime green as a sharp accent for interactive elements, indigo for depth, and grays/white for a clean canvas.

---

## Phases

### Phase 1 - MVP (Single User, Core Functionality)
Break this phase into **smaller milestones** so we don’t overload:

**Milestone 1: Setup**
- [ ] Installer: DB setup + first user creation.
- [ ] Basic auth (login, logout, password hash).

**Milestone 2: Books**
- [ ] Library view (list books).
- [ ] Add new book (title, cover, author).
- [ ] Publish toggle (public/private).

**Milestone 3: Pages**
- [ ] Add/edit page (text only, Markdown editor).
- [ ] Render Markdown for reading.
- [ ] TOC auto-generated.

**Milestone 4: Reorder & Navigation**
- [ ] Reorder pages with drag-and-drop.
- [ ] Prev/next navigation inside book.

**Milestone 5: Polish**
- [ ] Minimal CSS design system (Inter + Georgia typography, neutral colors, max-width layout).
- [ ] Test on Hostinger Cloud.

### Phase 2 - Media + Polish
- [ ] Picture pages with captions.
- [ ] Section divider pages.
- [ ] Image uploads inside text pages.
- [ ] Full-screen reading mode.
- [ ] Keyboard navigation.

### Phase 3 - Extras & Expansion
- [ ] Page version history with diffs.
- [ ] Export book (Markdown/HTML).
- [ ] Book search.
- [ ] Site-wide theming (optional colors, fonts).

### Phase 4 - Future Collaboration (Optional, much later)
- [ ] Add roles: owner, writer, reader.
- [ ] Invite users by email.
- [ ] Private books visible only to members.
- [ ] Basic access control.

---

## Success Criteria
- Phase 1: Working on shared hosting, can create, edit, reorder, and publish a book.
- Phase 2: Richer media support and improved reading experience.
- Phase 3: Export + advanced features for power users.
- Phase 4: Optional collaboration layer if needed in future.

## Risks
- Overbuilding too early (mitigated with strict phasing and milestones).
- Shared hosting file upload limitations (plan simple size validation).

---

## Next Step
Focus on **Phase 1, Milestone 1 (Setup)**. Build installer and basic user auth. Verify it works on Hostinger Cloud before moving forward.

