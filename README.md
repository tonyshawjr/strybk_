# Strybk_

A minimalist online book publishing tool inspired by 37signals' Writebook, built with vanilla PHP and MySQL for shared hosting compatibility.

## Context
- [REQUIREMENTS](./context/REQUIREMENTS.md)
- [DESIGN](./context/DESIGN.md)
- [TASKS](./context/TASKS.md)
- [PLAN](./context/PLAN.md)

## Features

### Core Functionality (Phase 1)
- 📚 **Multiple Books** - Create and manage multiple books per account
- ✍️ **Markdown Editor** - Clean, distraction-free writing with live preview
- 📖 **Beautiful Reading** - Typography-focused reader view
- 🔒 **Privacy Controls** - Toggle books between public/private
- 📱 **Responsive Design** - Works on all devices
- 🎨 **Minimal Design** - Focus on content, not chrome

### Coming Soon (Phase 2-3)
- 🖼️ Picture pages with captions
- 📑 Section divider pages
- 🔍 Book search
- 📤 Export to Markdown/HTML
- ⌨️ Keyboard navigation
- 📚 Version history

## Tech Stack

- **Backend**: PHP 8.2+
- **Database**: MySQL 5.7+ / MariaDB
- **Frontend**: Vanilla HTML/CSS/JS
- **Editor**: SimpleMDE
- **Markdown**: Parsedown
- **Hosting**: Shared hosting compatible (no Docker/terminal required)

## Installation

### Requirements
- PHP 8.2 or higher
- MySQL 5.7+ or MariaDB
- Apache/Nginx with mod_rewrite
- 50MB disk space minimum

### Quick Install

1. Upload files to your web host
2. Navigate to `yourdomain.com/install`
3. Follow the setup wizard:
   - Enter database credentials
   - Create your admin account
   - Configure site settings
4. Start writing!

### Manual Install

1. Create a MySQL database
2. Copy `app/config.example.php` to `app/config.php`
3. Update database credentials in `config.php`
4. Import `install/schema.sql` to your database
5. Navigate to your site and login

## Usage

### Creating Your First Book

1. Login to your dashboard
2. Click "New Book"
3. Add title, author, and optional cover image
4. Start adding pages
5. Toggle visibility when ready to publish

### Writing Pages

- **Text Page**: Full Markdown support with toolbar
- **Picture Page**: Upload image with caption
- **Section Page**: Visual divider between chapters

### Markdown Support

```markdown
# Heading 1
## Heading 2

**Bold** and *italic* text

> Blockquotes for emphasis

- Bullet lists
1. Numbered lists

`inline code` and code blocks

[Links](https://example.com)
![Images](image.jpg)
```

## Development

### Local Setup

```bash
# Clone the repo
git clone https://github.com/yourusername/strybk_.git
cd strybk_

# Install dependencies
npm install

# Run tests
npm test
npm run test:quick  # Smoke tests only

# Start local server (requires PHP)
php -S localhost:8000 -t public
```

### Testing

```bash
# Run all tests
npm test

# Run smoke tests
npm run test:quick

# Run with UI
npm run test:ui

# View test report
npm run e2e:report
```

## Project Structure

```
strybk_/
├── public/           # Web root
│   ├── index.php    # Front controller
│   ├── assets/      # CSS, JS, images
│   └── uploads/     # User uploads
├── app/             # Application code
│   ├── controllers/ # Request handlers
│   ├── models/      # Data models
│   ├── views/       # Templates
│   └── config.php   # Configuration
├── install/         # Installation wizard
├── storage/         # Logs, cache
├── tests/           # Test suites
├── context/         # Project documentation
└── .agent-os/       # Standards and specs
```

## Contributing

Contributions welcome! Please read our [contributing guidelines](CONTRIBUTING.md) first.

## License

MIT License - see [LICENSE](LICENSE) file for details.

## Inspiration

Inspired by [37signals' Writebook](https://once.com/writebook) - a beautiful, focused writing tool.

## Support

For issues and feature requests, please use the [GitHub issue tracker](https://github.com/yourusername/strybk_/issues).