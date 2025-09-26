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
                
                <!-- Controls Section -->
                <div class="book-controls">
                    <!-- Privacy Toggle Switch (like toolbar) -->
                    <div class="visibility-mode-toggle">
                        <input type="checkbox" 
                               id="visibility-toggle" 
                               class="toggle-input" 
                               <?= $book['is_public'] ? 'checked' : '' ?>
                               data-book-id="<?= $book['id'] ?>">
                        <label for="visibility-toggle" class="toggle-label">
                            <span class="toggle-icon private-icon">
                                <i class="fa-solid fa-lock"></i>
                            </span>
                            <span class="toggle-icon public-icon">
                                <i class="fa-solid fa-globe"></i>
                            </span>
                            <span class="toggle-slider"></span>
                        </label>
                    </div>
                    
                    <!-- View Book Button -->
                    <?php if ($book['is_public']): ?>
                        <a href="/read/<?= htmlspecialchars($book['slug']) ?>" target="_blank" class="view-book-btn" title="View Book">
                            <i class="fa-regular fa-eye"></i>
                        </a>
                    <?php endif; ?>
                </div>
                
                <!-- Public Link -->
                <?php if ($book['is_public']): ?>
                    <div class="public-link-section">
                        <span class="public-link-text"><?= htmlspecialchars($_SERVER['HTTP_HOST'] . '/read/' . $book['slug']) ?></span>
                        <button class="copy-icon-btn" onclick="copyLink(this)" title="Copy link">
                            <i class="fa-regular fa-copy"></i>
                        </button>
                    </div>
                <?php endif; ?>
                
                <!-- Book Actions -->
                <div class="book-actions-minimal">
                    <button class="action-icon" onclick="editBookDetails()" title="Edit details">
                        <i class="fa-regular fa-pen-to-square"></i>
                    </button>
                    <button class="action-icon" onclick="exportBook()" title="Export">
                        <i class="fa-solid fa-file-export"></i>
                    </button>
                    <button class="action-icon" onclick="shareBook()" title="Share">
                        <i class="fa-solid fa-arrow-up-from-bracket"></i>
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

/* Book Controls */
.book-controls {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 16px;
}

/* Visibility Toggle Switch (like toolbar) */
.visibility-mode-toggle {
    position: relative;
    display: inline-block;
    width: 72px;
    height: 36px;
}

.visibility-mode-toggle .toggle-input {
    display: none;
}

.visibility-mode-toggle .toggle-label {
    display: flex;
    align-items: center;
    justify-content: space-between;
    width: 100%;
    height: 100%;
    background: #F5F5F5;
    border-radius: 8px;
    padding: 4px;
    cursor: pointer;
    position: relative;
    transition: background 0.2s ease;
}

.visibility-mode-toggle .toggle-slider {
    position: absolute;
    width: 32px;
    height: 28px;
    background: white;
    border-radius: 6px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    transition: transform 0.2s ease;
    left: 4px;
}

.visibility-mode-toggle .toggle-icon {
    width: 32px;
    height: 28px;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1;
    font-size: 14px;
    transition: color 0.2s ease;
    line-height: 1;
}

.visibility-mode-toggle .private-icon {
    color: #111111;
}

.visibility-mode-toggle .public-icon {
    color: #666666;
}

.visibility-mode-toggle .toggle-icon i {
    display: flex;
    align-items: center;
    justify-content: center;
    width: 100%;
    height: 100%;
}

/* Checked state (public) */
.visibility-mode-toggle .toggle-input:checked + .toggle-label .toggle-slider {
    transform: translateX(36px);
}

.visibility-mode-toggle .toggle-input:checked + .toggle-label .private-icon {
    color: #666666;
}

.visibility-mode-toggle .toggle-input:checked + .toggle-label .public-icon {
    color: #111111;
}

/* View Book Button */
.view-book-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 36px;
    height: 36px;
    border-radius: 8px;
    color: #666666;
    text-decoration: none;
    transition: all 0.2s ease;
}

.view-book-btn:hover {
    background: #F5F5F5;
    color: #111111;
}

/* Public Link Section */
.public-link-section {
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 20px;
}

.public-link-text {
    font-size: 13px;
    color: #666666;
    font-family: 'Inter', sans-serif;
}

.copy-icon-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px;
    height: 24px;
    border: none;
    background: transparent;
    border-radius: 4px;
    color: #666666;
    cursor: pointer;
    transition: all 0.2s ease;
}

.copy-icon-btn:hover {
    background: #F5F5F5;
    color: #111111;
}

.copy-icon-btn.copied {
    color: #4CAF50;
}

/* Book Actions Minimal */
.book-actions-minimal {
    display: flex;
    gap: 8px;
}

.action-icon {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 32px;
    height: 32px;
    border: none;
    background: transparent;
    border-radius: 6px;
    color: #666666;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 16px;
}

.action-icon:hover {
    background: #F5F5F5;
    color: #111111;
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

/* List View - Table of Contents Style */
.list-view {
    background: transparent;
    border: none;
    border-radius: 0;
    overflow: visible;
}

.pages-list {
    padding: 0;
}

.page-list-link {
    text-decoration: none !important;
    color: inherit;
    display: block;
}

.page-list-link:hover {
    text-decoration: none !important;
}

.page-list-item {
    display: flex;
    align-items: baseline;
    padding: 12px 0;
    border: none;
    transition: all 0.2s ease;
    position: relative;
}

.page-list-item:after {
    content: '';
    position: absolute;
    bottom: 50%;
    left: 0;
    right: 0;
    border-bottom: 1px dotted #E5E5E5;
    z-index: 0;
}

.page-list-item:last-child {
    border-bottom: none;
}

.drag-handle {
    display: none;
    align-items: center;
    justify-content: center;
    width: 20px;
    margin-right: 12px;
    color: #999999;
    font-size: 12px;
    position: absolute;
    left: -24px;
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
    background: transparent;
}

.page-list-item:hover .page-title {
    text-decoration: underline;
    text-underline-offset: 3px;
}

.page-list-item:hover .page-word-count {
    text-decoration: none;
}

.page-list-item.active {
    background: transparent;
    border-left: none;
}

.page-list-item.active::before {
    content: '';
    position: absolute;
    left: -16px;
    width: 8px;
    height: 8px;
    background: #111111;
    border-radius: 50%;
    top: 50%;
    transform: translateY(-50%);
}

.page-title {
    color: #111111;
    font-size: 15px;
    font-weight: 400;
    line-height: 1.6;
    padding-right: 8px;
    background: white;
    z-index: 1;
    position: relative;
}

.page-word-count {
    color: #999999;
    font-size: 14px;
    margin-left: auto;
    padding-left: 8px;
    background: white;
    z-index: 1;
    position: relative;
}

.list-section {
    margin-top: 32px;
    padding-top: 16px;
    border-top: 1px solid #E5E5E5;
}

.list-section h5 {
    padding: 0 0 8px 0;
    background: transparent;
    border: none;
    font-size: 16px;
    font-weight: 700;
    color: #111111;
    margin: 0 0 8px 0;
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

// Load saved view preference
const savedView = localStorage.getItem('bookEditView') || 'gallery';
if (savedView === 'list') {
    document.querySelectorAll('.view-btn').forEach(b => b.classList.remove('active'));
    document.querySelector('.view-btn[data-view="list"]').classList.add('active');
    document.querySelectorAll('.pages-view').forEach(v => v.classList.remove('active'));
    document.getElementById('list-view').classList.add('active');
}

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
        
        // Save preference
        localStorage.setItem('bookEditView', view);
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

// Visibility toggle handler
document.getElementById('visibility-toggle')?.addEventListener('change', function() {
    const bookId = this.dataset.bookId;
    const isPublic = this.checked;
    const toggleInput = this;
    
    // Disable during update
    toggleInput.disabled = true;
    
    // Send update to server
    fetch('/books/' + bookId + '/visibility', {
        method: 'POST',
        headers: {
            'X-CSRF-Token': '<?= $auth->csrfToken() ?>'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const actualStatus = data.is_public;
            
            // Update toggle if server state differs
            toggleInput.checked = actualStatus;
            
            // Handle view button and public link visibility
            const viewBtn = document.querySelector('.view-book-btn');
            const publicLink = document.querySelector('.public-link-section');
            
            if (actualStatus) {
                // Show view button if not exists
                if (!viewBtn) {
                    const toggle = document.querySelector('.visibility-mode-toggle');
                    const viewHtml = `<a href="/read/<?= htmlspecialchars($book['slug']) ?>" target="_blank" class="view-book-btn" title="View Book">
                        <i class="fa-regular fa-eye"></i>
                    </a>`;
                    toggle.insertAdjacentHTML('afterend', viewHtml);
                }
                
                // Show public link if not exists
                if (!publicLink) {
                    const controlsDiv = document.querySelector('.book-controls');
                    const linkHtml = `
                        <div class="public-link-section">
                            <span class="public-link-text">${window.location.host}/read/<?= htmlspecialchars($book['slug']) ?></span>
                            <button class="copy-icon-btn" onclick="copyLink(this)" title="Copy link">
                                <i class="fa-regular fa-copy"></i>
                            </button>
                        </div>`;
                    controlsDiv.insertAdjacentHTML('afterend', linkHtml);
                }
            } else {
                // Hide view button and public link
                viewBtn?.remove();
                publicLink?.remove();
            }
        } else {
            // Revert toggle on error
            toggleInput.checked = !isPublic;
            alert('Failed to update book visibility. Please try again.');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        toggleInput.checked = !isPublic;
        alert('An error occurred. Please try again.');
    })
    .finally(() => {
        toggleInput.disabled = false;
    });
})

// Copy link function
function copyLink(button) {
    const linkText = button.previousElementSibling.textContent;
    
    // Create temporary input to copy text
    const tempInput = document.createElement('input');
    tempInput.value = 'https://' + linkText;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand('copy');
    document.body.removeChild(tempInput);
    
    // Visual feedback
    button.classList.add('copied');
    const originalIcon = button.innerHTML;
    button.innerHTML = '<i class="fa-solid fa-check"></i>';
    
    setTimeout(() => {
        button.classList.remove('copied');
        button.innerHTML = originalIcon;
    }, 2000);
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