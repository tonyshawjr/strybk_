<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <meta http-equiv="Expires" content="0">
    <title><?= htmlspecialchars($title) ?> - Strybk</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        :root {
            --primary: #111111;
            --indigo: #2E1A47;
            --lime: #A8FF60;
            --gray-50: #f9fafb;
            --gray-100: #f3f4f6;
            --gray-200: #e5e7eb;
            --gray-300: #d1d5db;
            --gray-400: #9ca3af;
            --gray-500: #6b7280;
            --gray-600: #4b5563;
            --gray-700: #374151;
            --gray-800: #1f2937;
            --gray-900: #111827;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: white;
            color: var(--gray-900);
            line-height: 1.8;
        }
        
        .reader-container {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar TOC */
        .toc-sidebar {
            width: 300px;
            background: var(--gray-50);
            border-right: 1px solid var(--gray-200);
            padding: 2rem 0;
            position: fixed;
            height: 100vh;
            overflow-y: auto;
            transition: transform 0.3s;
        }
        
        .toc-sidebar.hidden {
            transform: translateX(-100%);
        }
        
        .toc-header {
            padding: 0 1.5rem;
            margin-bottom: 2rem;
        }
        
        .book-title {
            font-size: 1.25rem;
            font-weight: bold;
            color: var(--gray-900);
            margin-bottom: 0.5rem;
        }
        
        .book-author {
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }
        
        .book-stats {
            font-size: 0.75rem;
            color: var(--gray-500);
        }
        
        .toc-list {
            list-style: none;
        }
        
        .toc-item {
            padding: 0.75rem 1.5rem;
            cursor: pointer;
            transition: background 0.2s;
            border-left: 3px solid transparent;
        }
        
        .toc-item:hover {
            background: var(--gray-100);
        }
        
        .toc-item.current {
            background: white;
            border-left-color: var(--primary);
        }
        
        .toc-item.section {
            padding-left: 2.5rem;
            font-size: 0.875rem;
        }
        
        .toc-link {
            color: var(--gray-700);
            text-decoration: none;
            display: block;
        }
        
        .toc-number {
            display: inline-block;
            margin-right: 0.5rem;
            color: var(--gray-500);
            font-size: 0.875rem;
        }
        
        /* Main content */
        .reader-main {
            flex: 1;
            margin-left: 300px;
            transition: margin-left 0.3s;
        }
        
        .reader-main.full-width {
            margin-left: 0;
        }
        
        .reader-header {
            background: white;
            border-bottom: 1px solid var(--gray-200);
            padding: 1rem 2rem;
            position: sticky;
            top: 0;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .breadcrumb {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: var(--gray-600);
            margin-bottom: 0.5rem;
        }
        
        .breadcrumb-item {
            color: var(--gray-600);
            text-decoration: none;
            transition: color 0.2s;
        }
        
        .breadcrumb-item:hover {
            color: var(--primary);
        }
        
        .breadcrumb-separator {
            margin: 0 0.5rem;
            color: var(--gray-400);
        }
        
        .breadcrumb-current {
            color: var(--gray-900);
            font-weight: 500;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .reader-controls {
            display: flex;
            gap: 1rem;
            align-items: center;
        }
        
        .toggle-toc {
            background: none;
            border: none;
            cursor: pointer;
            padding: 0.5rem;
            color: var(--gray-600);
            transition: color 0.2s;
        }
        
        .toggle-toc:hover {
            color: var(--primary);
        }
        
        .progress-bar {
            height: 4px;
            background: var(--gray-200);
            border-radius: 2px;
            overflow: hidden;
            width: 200px;
        }
        
        .progress-fill {
            height: 100%;
            background: var(--primary);
            transition: width 0.3s;
        }
        
        .reader-content {
            max-width: 700px;
            margin: 0 auto;
            padding: 3rem 2rem 6rem;
        }
        
        .page-header {
            margin-bottom: 3rem;
            padding-bottom: 2rem;
            border-bottom: 1px solid var(--gray-200);
        }
        
        .page-kind {
            font-size: 0.875rem;
            color: var(--gray-500);
            text-transform: uppercase;
            letter-spacing: 0.1em;
            margin-bottom: 0.5rem;
        }
        
        .page-title {
            font-size: 2.5rem;
            line-height: 1.2;
            margin-bottom: 0.5rem;
        }
        
        .page-meta {
            font-size: 0.875rem;
            color: var(--gray-500);
        }
        
        /* Content styles */
        .page-content {
            font-size: 1.125rem;
            line-height: 1.8;
        }
        
        .page-content h1 {
            font-size: 2rem;
            margin: 2rem 0 1rem;
        }
        
        .page-content h2 {
            font-size: 1.5rem;
            margin: 2rem 0 1rem;
        }
        
        .page-content h3 {
            font-size: 1.25rem;
            margin: 1.5rem 0 0.75rem;
        }
        
        .page-content p {
            margin-bottom: 1.5rem;
        }
        
        .page-content strong,
        .page-content b {
            font-weight: 600;
            color: var(--gray-900);
        }
        
        .page-content em,
        .page-content i {
            font-style: italic;
        }
        
        .page-content ul,
        .page-content ol {
            margin-bottom: 1.5rem;
            padding-left: 2rem;
        }
        
        .page-content li {
            margin-bottom: 0.5rem;
        }
        
        .page-content blockquote {
            border-left: 4px solid #666666;
            padding-left: 1.5rem;
            margin: 2rem 0;
            font-style: italic;
            color: var(--gray-700);
        }
        
        .page-content pre {
            background: var(--gray-900);
            color: var(--gray-100);
            padding: 1rem;
            border-radius: 6px;
            overflow-x: auto;
            margin: 1.5rem 0;
        }
        
        .page-content code {
            background: var(--gray-100);
            padding: 0.125rem 0.375rem;
            border-radius: 3px;
            font-family: 'Monaco', monospace;
            font-size: 0.875em;
        }
        
        .page-content pre code {
            background: none;
            padding: 0;
        }
        
        .page-content img {
            max-width: 100%;
            height: auto;
            margin: 2rem 0;
            border-radius: 8px;
        }
        
        /* Picture page */
        .picture-page {
            text-align: center;
            padding: 3rem 0;
        }
        
        .picture-page img {
            max-width: 100%;
            height: auto;
            max-height: 70vh;
            border-radius: 8px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
        }
        
        .picture-caption {
            margin-top: 2rem;
            font-style: italic;
            color: var(--gray-600);
        }
        
        /* Divider page */
        .divider-page {
            text-align: center;
            padding: 6rem 0;
        }
        
        .divider-ornament {
            font-size: 2rem;
            color: var(--gray-400);
            margin-bottom: 1rem;
        }
        
        .divider-text {
            font-style: italic;
            color: var(--gray-600);
        }
        
        /* Navigation */
        .page-navigation {
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem 2rem;
            margin-top: 4rem;
            border-top: 1px solid var(--gray-200);
        }
        
        .nav-button {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem 1.25rem;
            background: var(--gray-100);
            color: var(--gray-700);
            text-decoration: none;
            border-radius: 6px;
            transition: all 0.2s;
        }
        
        .nav-button:hover {
            background: var(--primary);
            color: white;
        }
        
        .nav-button.disabled {
            opacity: 0.5;
            pointer-events: none;
        }
        
        /* Next button with title */
        .nav-button.next-with-title {
            display: inline-flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1.5rem;
            background: white;
            border: 2px solid var(--primary);
            border-radius: 50px;
            color: var(--primary);
            font-weight: 500;
            position: relative;
            white-space: nowrap;
        }
        
        .nav-button.next-with-title:hover {
            background: var(--primary);
            color: white;
        }
        
        .next-label {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .next-prefix {
            font-size: 0.875rem;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            font-weight: 600;
        }
        
        .next-title {
            font-size: 0.9375rem;
            font-weight: 400;
            max-width: 300px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        .next-arrow {
            font-size: 1.125rem;
            line-height: 1;
        }
        
        /* Mobile responsive */
        @media (max-width: 768px) {
            .toc-sidebar {
                transform: translateX(-100%);
                z-index: 20;
            }
            
            .toc-sidebar.visible {
                transform: translateX(0);
            }
            
            .reader-main {
                margin-left: 0;
            }
            
            .reader-content {
                padding: 2rem 1rem;
            }
            
            .page-title {
                font-size: 2rem;
            }
            
            .nav-button.next-with-title {
                padding: 0.75rem 1.25rem;
            }
            
            .next-title {
                font-size: 0.875rem;
                max-width: 200px;
            }
            
            .page-content {
                font-size: 1rem;
            }
        }
        
        /* Keyboard shortcuts help modal */
        .keyboard-help-modal {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .keyboard-help-content {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            max-width: 400px;
            position: relative;
        }
        
        .keyboard-help-content h3 {
            margin: 0 0 1.5rem 0;
            color: var(--gray-900);
        }
        
        .keyboard-help-content dl {
            margin: 0;
        }
        
        .keyboard-help-content dt {
            display: inline-block;
            width: 80px;
            font-weight: 600;
            color: var(--gray-700);
            margin-bottom: 0.5rem;
        }
        
        .keyboard-help-content dd {
            display: inline-block;
            margin: 0 0 0.5rem 0;
            color: var(--gray-600);
        }
        
        .close-help {
            position: absolute;
            top: 1rem;
            right: 1rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: var(--gray-400);
            cursor: pointer;
            padding: 0.25rem;
            line-height: 1;
        }
        
        .close-help:hover {
            color: var(--gray-600);
        }
    </style>
</head>
<body>
    <div class="reader-container">
        <!-- Table of Contents Sidebar -->
        <aside class="toc-sidebar" id="toc-sidebar">
            <div class="toc-header">
                <div class="book-title"><?= htmlspecialchars($book['title']) ?></div>
                <?php if ($book['author']): ?>
                    <div class="book-author">by <?= htmlspecialchars($book['author']) ?></div>
                <?php endif; ?>
                <div class="book-stats">
                    <?= number_format($totalWords) ?> words • 
                    <?= $readingTime ?> min read
                </div>
            </div>
            
            <ul class="toc-list">
                <?php foreach ($toc as $item): ?>
                    <li class="toc-item <?= $item['kind'] ?> <?= $item['is_current'] ? 'current' : '' ?>">
                        <a href="/read/<?= htmlspecialchars($book['slug']) ?>/<?= htmlspecialchars($item['slug']) ?>" class="toc-link">
                            <span class="toc-number"><?= $item['number'] ?></span>
                            <?= htmlspecialchars($item['title']) ?>
                        </a>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        
        <!-- Main Content -->
        <main class="reader-main" id="reader-main">
            <header class="reader-header">
                <nav class="breadcrumb">
                    <a href="/books" class="breadcrumb-item">Library</a>
                    <span class="breadcrumb-separator">›</span>
                    <a href="/books/<?= htmlspecialchars($book['slug']) ?>" class="breadcrumb-item"><?= htmlspecialchars($book['title']) ?></a>
                    <span class="breadcrumb-separator">›</span>
                    <span class="breadcrumb-current"><?= htmlspecialchars($currentPage['title']) ?></span>
                </nav>
                <div class="reader-controls">
                    <button class="toggle-toc" id="toggle-toc" title="Toggle Table of Contents">
                        <i class="fa-solid fa-bars"></i>
                    </button>
                    
                    <div class="progress-bar">
                        <div class="progress-fill" style="width: <?= $progress ?>%"></div>
                    </div>
                    
                    <span style="font-size: 0.875rem; color: var(--gray-600);">
                        <?= $progress ?>% complete
                    </span>
                </div>
                
                <?php if ($auth->check() && $book['created_by'] == $auth->user()['id']): ?>
                    <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" style="color: #111111; text-decoration: none;">
                        Edit Book
                    </a>
                <?php endif; ?>
            </header>
            
            <article class="reader-content">
                <?php $displayKind = $currentPage['display_kind'] ?? 'chapter'; ?>
                <?php if ($displayKind === 'picture'): ?>
                    <div class="picture-page">
                        <?php if (isset($currentPage['image_path']) && $currentPage['image_path']): ?>
                            <img src="<?= htmlspecialchars($currentPage['image_path']) ?>" alt="<?= htmlspecialchars($currentPage['title']) ?>">
                        <?php endif; ?>
                        <?php if ($currentPage['title']): ?>
                            <div class="picture-caption"><?= htmlspecialchars($currentPage['title']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php elseif ($displayKind === 'divider'): ?>
                    <div class="divider-page">
                        <div class="divider-ornament">❦</div>
                        <?php if ($currentPage['title']): ?>
                            <div class="divider-text"><?= htmlspecialchars($currentPage['title']) ?></div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <header class="page-header">
                        <div class="page-kind"><?= ucfirst($displayKind) ?></div>
                        <h1 class="page-title"><?= htmlspecialchars($currentPage['title']) ?></h1>
                        <div class="page-meta">
                            <?= number_format($currentPage['word_count']) ?> words
                        </div>
                    </header>
                    
                    <div class="page-content">
                        <?php 
                        // Display content - check if it's HTML from the editor or markdown
                        $content = $currentPage['content'] ?? '';
                        
                        // Check for common HTML tags that would come from the editor
                        $htmlTags = ['<p>', '<p ', '<strong>', '<em>', '<blockquote>', '<ul>', '<ol>', '<h1>', '<h2>', '<h3>', '<div>', '<br'];
                        $isHtml = false;
                        foreach ($htmlTags as $tag) {
                            if (stripos($content, $tag) !== false) {
                                $isHtml = true;
                                break;
                            }
                        }
                        
                        if ($isHtml) {
                            // Content is HTML from the editor, display directly
                            echo $content;
                        } else {
                            // Content is markdown or plain text, use the Parsedown-rendered version
                            echo $currentPage['rendered_content'] ?? $content;
                        }
                        ?>
                    </div>
                <?php endif; ?>
                
                <!-- Page Navigation -->
                <?php 
                // Debug output - remove after testing
                if (isset($nextPage) && $nextPage) {
                    echo "<!-- Debug: Next page ID: {$nextPage['id']}, Title: {$nextPage['title']}, Slug: {$nextPage['slug']} -->\n";
                }
                if (isset($currentPage)) {
                    echo "<!-- Debug: Current page ID: {$currentPage['id']}, Title: {$currentPage['title']} -->\n";
                }
                ?>
                <?php if (isset($nextPage) && $nextPage): ?>
                <nav class="page-navigation">
                    <a href="/read/<?= htmlspecialchars($book['slug']) ?>/<?= htmlspecialchars($nextPage['slug']) ?>" class="nav-button next-with-title">
                        <span class="next-label">
                            <span class="next-prefix">NEXT:</span>
                            <span class="next-title"><?= htmlspecialchars($nextPage['title'] ?? '') ?></span>
                        </span>
                        <span class="next-arrow">→</span>
                    </a>
                </nav>
                <?php endif; ?>
            </article>
        </main>
    </div>
    
    <script>
        // Toggle TOC sidebar
        const tocSidebar = document.getElementById('toc-sidebar');
        const readerMain = document.getElementById('reader-main');
        const toggleBtn = document.getElementById('toggle-toc');
        
        toggleBtn.addEventListener('click', () => {
            tocSidebar.classList.toggle('hidden');
            readerMain.classList.toggle('full-width');
            
            // On mobile, use different class
            if (window.innerWidth <= 768) {
                tocSidebar.classList.toggle('visible');
            }
        });
        
        // Enhanced Keyboard navigation
        document.addEventListener('keydown', (e) => {
            // Don't trigger shortcuts when typing in inputs
            if (e.target.tagName === 'INPUT' || e.target.tagName === 'TEXTAREA') {
                return;
            }
            
            if (e.key === 'ArrowRight' || e.key === 'd' || e.key === 'D') {
                // Next page
                const nextLink = document.querySelector('.nav-button.next-with-title');
                if (nextLink) {
                    nextLink.click();
                }
            } else if (e.key === 't' || e.key === 'T') {
                // Toggle TOC
                toggleBtn.click();
            } else if (e.key === 'Escape') {
                // Hide TOC
                if (!tocSidebar.classList.contains('hidden')) {
                    tocSidebar.classList.add('hidden');
                    readerMain.classList.add('full-width');
                }
            } else if (e.key === 'h' || e.key === 'H' || e.key === '?') {
                // Show help (keyboard shortcuts)
                showKeyboardHelp();
            }
        });
        
        // Show keyboard shortcuts help
        function showKeyboardHelp() {
            const helpModal = document.createElement('div');
            helpModal.className = 'keyboard-help-modal';
            helpModal.innerHTML = `
                <div class="keyboard-help-content">
                    <h3>Keyboard Shortcuts</h3>
                    <button class="close-help" onclick="this.parentElement.parentElement.remove()">×</button>
                    <dl>
                        <dt>→ / D</dt><dd>Next page</dd>
                        <dt>T</dt><dd>Toggle table of contents</dd>
                        <dt>ESC</dt><dd>Hide table of contents</dd>
                        <dt>? / H</dt><dd>Show this help</dd>
                    </dl>
                </div>
            `;
            document.body.appendChild(helpModal);
            
            // Close on click outside
            helpModal.addEventListener('click', (e) => {
                if (e.target === helpModal) {
                    helpModal.remove();
                }
            });
            
            // Close on Escape
            const closeOnEsc = (e) => {
                if (e.key === 'Escape') {
                    helpModal.remove();
                    document.removeEventListener('keydown', closeOnEsc);
                }
            };
            document.addEventListener('keydown', closeOnEsc);
        }
    </script>
</body>
</html>