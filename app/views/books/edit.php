<?php 
$showBackButton = true;
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container">
    <div class="book-detail">
        <!-- Book Info Section -->
        <div class="book-info">
            <?php if ($book['cover_path']): ?>
                <div class="book-cover-display">
                    <img src="<?= htmlspecialchars($book['cover_path']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                </div>
            <?php else: ?>
                <div class="book-cover-display" style="background: <?= getBookColor($book['title']) ?>">
                    <div class="book-cover-content">
                        <h2><?= htmlspecialchars($book['title']) ?></h2>
                        <?php if ($book['subtitle']): ?>
                            <p><?= htmlspecialchars($book['subtitle']) ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="book-meta-section">
                <h1 class="book-title-display"><?= htmlspecialchars($book['title']) ?></h1>
                <p class="book-author-display"><?= htmlspecialchars($book['author'] ?? '37signals') ?></p>
                
                <!-- View Book Link -->
                <?php if ($book['is_public']): ?>
                    <a href="/read/<?= htmlspecialchars($book['slug']) ?>" target="_blank" class="view-book-link">
                        <i class="fa-regular fa-eye"></i>
                        View Book
                    </a>
                <?php endif; ?>
                
                <!-- Privacy Toggle -->
                <div class="privacy-toggle-container">
                    <button class="privacy-toggle <?= $book['is_public'] ? 'public' : 'private' ?>" 
                            data-book-id="<?= $book['id'] ?>" 
                            data-current="<?= $book['is_public'] ? 'public' : 'private' ?>">
                        <span class="toggle-icon">
                            <?php if ($book['is_public']): ?>
                                <i class="fa-regular fa-eye"></i>
                            <?php else: ?>
                                <i class="fa-solid fa-lock"></i>
                            <?php endif; ?>
                        </span>
                        <span class="toggle-text"><?= $book['is_public'] ? 'Public' : 'Private' ?></span>
                    </button>
                    
                    <?php if ($book['is_public']): ?>
                        <div class="public-link">
                            <input type="text" readonly value="<?= htmlspecialchars($_SERVER['HTTP_HOST'] . '/read/' . $book['slug']) ?>" class="link-input">
                            <button class="copy-link" onclick="copyLink(this)">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- Book Actions -->
                <div class="book-actions">
                    <button class="btn-icon" onclick="editBookDetails()" title="Edit details">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button class="btn-icon" onclick="shareBook()" title="Share">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    </button>
                    <button class="btn-icon" onclick="exportBook()" title="Export">
                        <i class="fa-solid fa-file-export"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Pages/Chapters Section -->
        <div class="pages-section">
            <div class="pages-header">
                <div class="view-toggle">
                    <button class="view-btn active" data-view="gallery">
                        <i class="fa-solid fa-grip"></i>
                    </button>
                    <button class="view-btn" data-view="list">
                        <i class="fa-solid fa-list"></i>
                    </button>
                </div>
                
                <div class="pages-actions">
                    <div class="mode-toggle">
                        <button class="mode-btn active" data-mode="edit">
                            <i class="fa-regular fa-pen-to-square"></i>
                            <span>Edit</span>
                        </button>
                        <button class="mode-btn" data-mode="reorder">
                            <i class="fa-solid fa-arrows-up-down"></i>
                            <span>Reorder</span>
                        </button>
                    </div>
                    <a href="/books/<?= $book['id'] ?>/pages/new" class="add-page-btn">
                        <i class="fa-solid fa-plus"></i>
                    </a>
                </div>
            </div>
            
            <!-- Gallery View -->
            <div id="gallery-view" class="pages-view gallery-view active" data-mode="edit">
                <div class="pages-grid" id="pages-grid">
                    <?php foreach ($pages as $page): ?>
                        <a href="/pages/<?= $page['id'] ?>/edit" class="page-card-link">
                            <div class="page-card" data-id="<?= $page['id'] ?>">
                                <div class="page-thumbnail">
                                    <div class="page-content-preview">
                                        <h3><?= htmlspecialchars($page['title']) ?></h3>
                                        <p><?= htmlspecialchars(substr(strip_tags($page['content'] ?? ''), 0, 100)) ?>...</p>
                                    </div>
                                    <div class="drag-indicator">
                                        <i class="fa-solid fa-grip-vertical"></i>
                                    </div>
                                </div>
                                <div class="page-info">
                                    <h4><?= htmlspecialchars($page['title']) ?></h4>
                                    <span class="word-count"><?= number_format(str_word_count($page['content'] ?? '')) ?> words</span>
                                </div>
                            </div>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>
            
            <!-- List View -->
            <div id="list-view" class="pages-view list-view" data-mode="edit">
                <div class="pages-list" id="pages-list">
                    <div class="list-header">
                        <span class="chapter-indicator">Welcome</span>
                        <span class="word-count-header">134 words</span>
                    </div>
                    <?php foreach ($pages as $index => $page): ?>
                        <a href="/pages/<?= $page['id'] ?>/edit" class="page-list-link">
                            <div class="page-list-item <?= $index === 0 ? 'active' : '' ?>" data-id="<?= $page['id'] ?>">
                                <span class="drag-handle">
                                    <i class="fa-solid fa-grip-vertical"></i>
                                </span>
                                <span class="page-title"><?= htmlspecialchars($page['title']) ?></span>
                                <span class="page-word-count"><?= number_format(str_word_count($page['content'] ?? '')) ?> words</span>
                            </div>
                        </a>
                    <?php endforeach; ?>
                    
                    <?php if (count($pages) > 10): ?>
                        <div class="list-section">
                            <h5>Appendix</h5>
                            <!-- Additional pages go here -->
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Generate consistent colors for book covers based on title
function getBookColor($title) {
    $colors = [
        'linear-gradient(135deg, #111111 0%, #333333 100%)',
        'linear-gradient(135deg, #333333 0%, #666666 100%)',
        'linear-gradient(135deg, #666666 0%, #999999 100%)',
        'linear-gradient(135deg, #111111 0%, #666666 100%)',
        '#111111',
        '#333333',
        '#666666',
    ];
    $hash = crc32($title);
    $index = abs($hash) % count($colors);
    return $colors[$index];
}
?>

<style>
/* Book Detail Layout */
.book-detail {
    display: grid;
    grid-template-columns: 400px 1fr;
    gap: 60px;
    padding: 40px 0;
}

/* Book Info Section */
.book-info {
    position: sticky;
    top: 40px;
    height: fit-content;
}

.book-cover-display {
    width: 256px;
    height: 340px;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 24px rgba(0,0,0,0.08);
    margin-bottom: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.book-cover-display img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-cover-content {
    text-align: center;
    padding: 24px;
    color: white;
}

.book-cover-content h2 {
    font-size: 24px;
    font-weight: 700;
    margin-bottom: 8px;
}

.book-title-display {
    font-size: 32px;
    font-weight: 700;
    color: #111111;
    margin: 0 0 8px 0;
}

.book-author-display {
    font-size: 18px;
    color: #666666;
    margin: 0 0 16px 0;
}

/* View Book Link */
.view-book-link {
    display: inline-block;
    padding: 10px 20px;
    background: #111111;
    color: white;
    border-radius: 6px;
    text-decoration: none;
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 20px;
    transition: all 0.2s ease;
}

.view-book-link i {
    margin-right: 6px;
}

.view-book-link:hover {
    background: #333333;
    color: white;
    transform: translateY(-1px);
}

/* Privacy Toggle */
.privacy-toggle-container {
    margin-bottom: 24px;
}

.privacy-toggle {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 8px 16px;
    border: 1px solid #E5E5E5;
    border-radius: 20px;
    background: white;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.privacy-toggle.public {
    background: #F5F5F5;
    border-color: #111111;
    color: #111111;
}

.privacy-toggle.private {
    background: #111111;
    border-color: #111111;
    color: white;
}

.public-link {
    display: flex;
    gap: 8px;
    margin-top: 12px;
}

.link-input {
    flex: 1;
    padding: 8px 12px;
    border: 1px solid #E5E5E5;
    border-radius: 6px;
    font-size: 12px;
    color: #666666;
    background: #FAFAFA;
}

.copy-link {
    padding: 8px;
    border: 1px solid #E5E5E5;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.copy-link:hover {
    background: #F5F5F5;
}

/* Book Actions */
.book-actions {
    display: flex;
    gap: 8px;
}

.btn-icon {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #E5E5E5;
    border-radius: 6px;
    background: white;
    cursor: pointer;
    transition: all 0.2s ease;
}

.btn-icon:hover {
    background: #F5F5F5;
    border-color: #111111;
}

/* Pages Section */
.pages-section {
    min-height: 600px;
}

.pages-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}

.view-toggle {
    display: flex;
    gap: 4px;
    padding: 4px;
    background: #F5F5F5;
    border-radius: 6px;
}

.view-btn {
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border: none;
    background: transparent;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #999999;
}

.view-btn.active {
    background: white;
    color: #111111;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.pages-actions {
    display: flex;
    align-items: center;
    gap: 16px;
}

.mode-toggle {
    display: flex;
    gap: 0;
    background: #F5F5F5;
    border-radius: 6px;
    padding: 2px;
}

.mode-btn {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border: none;
    background: transparent;
    border-radius: 4px;
    cursor: pointer;
    transition: all 0.2s ease;
    color: #666666;
    font-size: 14px;
    font-family: 'Inter', sans-serif;
    font-weight: 500;
}

.mode-btn i {
    font-size: 14px;
}

.mode-btn span {
    display: none;
}

.mode-btn.active {
    background: white;
    color: #111111;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

@media (min-width: 768px) {
    .mode-btn span {
        display: inline;
    }
}

.add-page-btn {
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #111111;
    border-radius: 50%;
    color: white;
    transition: all 0.2s ease;
}

.add-page-btn:hover {
    background: #333333;
    transform: scale(1.05);
}

/* Gallery View */
.pages-view {
    display: none;
}

.pages-view.active {
    display: block;
}

.pages-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
    gap: 24px;
}

.page-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

/* Edit mode styles */
[data-mode="edit"] .page-card-link {
    cursor: pointer;
}

[data-mode="edit"] .page-card {
    cursor: pointer;
}

[data-mode="edit"] .drag-indicator,
[data-mode="edit"] .drag-handle {
    display: none;
}

/* Reorder mode styles */
[data-mode="reorder"] .page-card-link {
    pointer-events: none;
}

[data-mode="reorder"] .page-card {
    cursor: move;
}

[data-mode="reorder"] .drag-indicator,
[data-mode="reorder"] .drag-handle {
    display: flex;
}

.page-thumbnail {
    aspect-ratio: 3/4;
    background: white;
    border: 1px solid #E5E5E5;
    border-radius: 4px;
    padding: 16px;
    margin-bottom: 8px;
    overflow: hidden;
    transition: all 0.2s ease;
    position: relative;
}

.drag-indicator {
    position: absolute;
    top: 8px;
    right: 8px;
    width: 24px;
    height: 24px;
    background: rgba(255,255,255,0.9);
    border-radius: 4px;
    display: none;
    align-items: center;
    justify-content: center;
    color: #666666;
    font-size: 12px;
}

[data-mode="edit"] .page-card:hover .page-thumbnail {
    border-color: #111111;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

[data-mode="reorder"] .page-card:hover .drag-indicator {
    background: #111111;
    color: white;
}

.page-content-preview {
    font-size: 8px;
    line-height: 1.4;
    color: #666666;
}

.page-content-preview h3 {
    font-size: 10px;
    font-weight: 600;
    margin-bottom: 8px;
    color: #111111;
}

.page-info h4 {
    font-size: 14px;
    font-weight: 600;
    color: #111111;
    margin: 0 0 4px 0;
}

.word-count {
    font-size: 12px;
    color: #999999;
}

/* List View */
.list-view {
    background: white;
    border: 1px solid #E5E5E5;
    border-radius: 8px;
    overflow: hidden;
}

.list-header {
    display: flex;
    justify-content: space-between;
    padding: 16px 20px;
    background: #FAFAFA;
    border-bottom: 1px solid #E5E5E5;
    font-weight: 600;
    color: #111111;
}

.page-list-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.page-list-item {
    display: flex;
    align-items: center;
    padding: 12px 20px;
    border-bottom: 1px solid #F0F0F0;
    transition: all 0.2s ease;
}

.drag-handle {
    display: none;
    align-items: center;
    justify-content: center;
    width: 20px;
    margin-right: 12px;
    color: #999999;
    font-size: 12px;
}

[data-mode="reorder"] .drag-handle:hover {
    color: #111111;
}

[data-mode="edit"] .page-list-link {
    cursor: pointer;
}

[data-mode="reorder"] .page-list-link {
    pointer-events: none;
    cursor: move;
}

.page-list-item:hover {
    background: #FAFAFA;
}

.page-list-item.active {
    background: #F5F5F5;
    border-left: 3px solid #111111;
}

.page-title {
    flex: 1;
    color: #111111;
    font-size: 14px;
}

.page-word-count {
    color: #999999;
    font-size: 14px;
    margin-left: auto;
}

.list-section {
    margin-top: 24px;
}

.list-section h5 {
    padding: 12px 20px;
    background: #FAFAFA;
    border-top: 1px solid #E5E5E5;
    border-bottom: 1px solid #E5E5E5;
    font-size: 14px;
    font-weight: 600;
    color: #111111;
    margin: 0;
}

/* Sortable styles */
.sortable-ghost {
    opacity: 0.4;
}

.sortable-drag {
    opacity: 0.8;
}

/* Responsive */
@media (max-width: 1024px) {
    .book-detail {
        grid-template-columns: 1fr;
        gap: 40px;
    }
    
    .book-info {
        position: static;
        display: flex;
        gap: 32px;
        align-items: flex-start;
    }
    
    .book-cover-display {
        width: 200px;
        height: 266px;
    }
}

@media (max-width: 768px) {
    .book-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }
    
    .pages-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 16px;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script>
// Current mode state
let currentMode = 'edit';
let gallerySortable = null;
let listSortable = null;

// Mode Toggle
document.querySelectorAll('.mode-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const mode = this.dataset.mode;
        
        // Update active button
        document.querySelectorAll('.mode-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Update mode
        currentMode = mode;
        document.querySelectorAll('.pages-view').forEach(view => {
            view.dataset.mode = mode;
        });
        
        // Enable/disable sortable based on mode
        if (mode === 'reorder') {
            enableSortable();
        } else {
            disableSortable();
        }
    });
});

// View Toggle
document.querySelectorAll('.view-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        const view = this.dataset.view;
        
        // Update active button
        document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
        this.classList.add('active');
        
        // Update active view
        document.querySelectorAll('.pages-view').forEach(v => v.classList.remove('active'));
        document.getElementById(view + '-view').classList.add('active');
    });
});

// Initialize SortableJS instances
function initializeSortable() {
    const galleryGrid = document.getElementById('pages-grid');
    if (galleryGrid) {
        gallerySortable = Sortable.create(galleryGrid, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.page-card',
            disabled: true, // Start disabled (edit mode is default)
            onEnd: function(evt) {
                updatePageOrder();
            }
        });
    }
    
    const pagesList = document.getElementById('pages-list');
    if (pagesList) {
        listSortable = Sortable.create(pagesList, {
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            handle: '.page-list-item',
            filter: '.list-header, .list-section',
            disabled: true, // Start disabled (edit mode is default)
            onEnd: function(evt) {
                updatePageOrder();
            }
        });
    }
}

// Enable sortable for reorder mode
function enableSortable() {
    if (gallerySortable) gallerySortable.option('disabled', false);
    if (listSortable) listSortable.option('disabled', false);
}

// Disable sortable for edit mode
function disableSortable() {
    if (gallerySortable) gallerySortable.option('disabled', true);
    if (listSortable) listSortable.option('disabled', true);
}

// Initialize on page load
initializeSortable();

// Update page order via AJAX
function updatePageOrder() {
    const pageIds = [];
    document.querySelectorAll('.page-card, .page-list-item').forEach(item => {
        if (item.dataset.id) {
            pageIds.push(item.dataset.id);
        }
    });
    
    fetch('/books/<?= $book['id'] ?>/pages/reorder', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-Token': '<?= $auth->csrfToken() ?>'
        },
        body: JSON.stringify({ page_ids: pageIds })
    });
}

// Privacy toggle
document.querySelector('.privacy-toggle')?.addEventListener('click', function() {
    const bookId = this.dataset.bookId;
    const current = this.dataset.current;
    const newStatus = current === 'public' ? 'private' : 'public';
    
    // Update UI optimistically
    this.classList.toggle('public');
    this.classList.toggle('private');
    this.dataset.current = newStatus;
    this.querySelector('.toggle-text').textContent = newStatus.charAt(0).toUpperCase() + newStatus.slice(1);
    
    // Update icon
    if (newStatus === 'public') {
        this.querySelector('.toggle-icon').innerHTML = `<i class="fa-regular fa-eye"></i>`;
        
        // Show View Book link
        const viewLink = document.querySelector('.view-book-link');
        if (!viewLink) {
            const bookMeta = document.querySelector('.book-meta-section');
            const linkHtml = `
                <a href="/read/<?= $book['slug'] ?>" target="_blank" class="view-book-link">
                    <i class="fa-regular fa-eye"></i>
                    View Book
                </a>
            `;
            // Insert after author
            const author = bookMeta.querySelector('.book-author-display');
            author.insertAdjacentHTML('afterend', linkHtml);
        }
        
        // Show public link
        if (!document.querySelector('.public-link')) {
            const linkHtml = `
                <div class="public-link">
                    <input type="text" readonly value="${window.location.host}/read/<?= $book['slug'] ?>" class="link-input">
                    <button class="copy-link" onclick="copyLink(this)">
                        <i class="fa-regular fa-copy"></i>
                    </button>
                </div>
            `;
            this.parentElement.insertAdjacentHTML('beforeend', linkHtml);
        }
    } else {
        this.querySelector('.toggle-icon').innerHTML = `<i class="fa-solid fa-lock"></i>`;
        // Hide View Book link
        document.querySelector('.view-book-link')?.remove();
        // Hide public link
        document.querySelector('.public-link')?.remove();
    }
    
    // Send update to server
    fetch('/books/' + bookId + '/visibility', {
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= $auth->csrfToken() ?>'
        }
    });
});

// Copy link function
function copyLink(button) {
    const input = button.previousElementSibling;
    input.select();
    document.execCommand('copy');
    
    // Visual feedback
    button.style.background = '#111111';
    button.style.borderColor = '#111111';
    button.style.color = 'white';
    setTimeout(() => {
        button.style.background = '';
        button.style.borderColor = '';
        button.style.color = '';
    }, 1000);
}

// Placeholder functions
function editBookDetails() {
    alert('Edit book details modal coming soon');
}

function shareBook() {
    alert('Share functionality coming soon');
}

function exportBook() {
    alert('Export functionality coming soon');
}
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>