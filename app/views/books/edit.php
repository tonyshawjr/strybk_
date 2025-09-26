<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <h1><?= htmlspecialchars($book['title']) ?></h1>
    </div>

    <div class="edit-layout">
        <div class="edit-sidebar">
            <div class="book-meta">
                <h2>Book Settings</h2>
                <form method="POST" action="/books/<?= $book['id'] ?>/update" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                    
                    <div class="form-group">
                        <label for="title">Title</label>
                        <input type="text" id="title" name="title" required 
                               value="<?= htmlspecialchars($book['title']) ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="subtitle">Subtitle</label>
                        <input type="text" id="subtitle" name="subtitle" 
                               value="<?= htmlspecialchars($book['subtitle'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="author">Author</label>
                        <input type="text" id="author" name="author" 
                               value="<?= htmlspecialchars($book['author'] ?? '') ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="cover">Cover Image</label>
                        <?php if ($book['cover_path']): ?>
                            <div class="current-cover">
                                <img src="<?= htmlspecialchars($book['cover_path']) ?>" alt="Current cover">
                            </div>
                        <?php endif; ?>
                        <input type="file" id="cover" name="cover" accept="image/jpeg,image/jpg,image/png,image/webp">
                        <p class="help-text">Upload new image to replace current cover</p>
                    </div>
                    
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_public" value="1" <?= $book['is_public'] ? 'checked' : '' ?>>
                            <span>Make this book public</span>
                        </label>
                    </div>
                    
                    <button type="submit" class="btn btn-primary btn-block">Update Book</button>
                </form>
                
                <hr class="divider">
                
                <div class="danger-zone">
                    <h3>Danger Zone</h3>
                    <form method="POST" action="/books/<?= $book['id'] ?>/delete" 
                          onsubmit="return confirm('Are you sure? This will delete the book and all its pages.')">
                        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                        <button type="submit" class="btn btn-danger btn-block">Delete Book</button>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="edit-main">
            <div class="pages-section">
                <div class="section-header">
                    <h2>Pages</h2>
                    <a href="/books/<?= $book['id'] ?>/pages/new" class="btn btn-primary">
                        <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                            <path d="M12 5v14M5 12h14"/>
                        </svg>
                        Add Page
                    </a>
                </div>
                
                <?php if (empty($pages)): ?>
                    <div class="empty-pages">
                        <p>No pages yet. Add your first page to start writing!</p>
                    </div>
                <?php else: ?>
                    <div class="pages-list" id="pages-list">
                        <?php foreach ($pages as $page): ?>
                            <?php 
                            // Map database kinds to display values
                            $displayKind = $page['kind'];
                            if ($displayKind === 'text') $displayKind = 'chapter';
                            ?>
                            <div class="page-item" data-id="<?= $page['id'] ?>">
                                <div class="drag-handle">
                                    <svg width="20" height="20" fill="currentColor">
                                        <circle cx="6" cy="10" r="1.5"/>
                                        <circle cx="6" cy="14" r="1.5"/>
                                        <circle cx="14" cy="10" r="1.5"/>
                                        <circle cx="14" cy="14" r="1.5"/>
                                    </svg>
                                </div>
                                
                                <div class="page-info">
                                    <h4><?= htmlspecialchars($page['title']) ?></h4>
                                    <span class="page-meta">
                                        <?= ucfirst($displayKind) ?> • 
                                        <?= number_format($page['word_count']) ?> words
                                    </span>
                                </div>
                                
                                <div class="page-actions">
                                    <a href="/pages/<?= $page['id'] ?>/edit" class="btn btn-sm">Edit</a>
                                    <form method="POST" action="/pages/<?= $page['id'] ?>/delete" class="inline-form">
                                        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                                        <button type="submit" class="btn btn-sm btn-ghost" 
                                                onclick="return confirm('Delete this page?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="book-preview">
                <h3>Preview</h3>
                <a href="/read/<?= htmlspecialchars($book['slug']) ?>" target="_blank" class="btn btn-secondary">
                    View Book
                </a>
                <?php if ($book['is_public']): ?>
                    <p class="help-text">Public URL: /read/<?= htmlspecialchars($book['slug']) ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<style>
.edit-layout {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    margin-top: 2rem;
}

.edit-sidebar {
    position: sticky;
    top: 2rem;
    height: fit-content;
}

.book-meta {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.book-meta h2 {
    margin: 0 0 1.5rem 0;
    font-size: 1.25rem;
}

.current-cover {
    margin-bottom: 1rem;
}

.current-cover img {
    width: 100%;
    border-radius: 6px;
}

.btn-block {
    width: 100%;
}

.divider {
    margin: 2rem 0;
    border: none;
    border-top: 1px solid #e5e7eb;
}

.danger-zone h3 {
    color: #dc2626;
    font-size: 1rem;
    margin-bottom: 1rem;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
}

.pages-section {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1.5rem;
}

.section-header h2 {
    margin: 0;
}

.empty-pages {
    text-align: center;
    padding: 3rem 1rem;
    color: #6b7280;
}

.pages-list {
    display: flex;
    flex-direction: column;
    gap: 0.75rem;
}

.page-item {
    display: flex;
    align-items: center;
    gap: 1rem;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 6px;
    transition: background 0.2s;
}

.page-item:hover {
    background: #f3f4f6;
}

.drag-handle {
    color: #9ca3af;
    cursor: move;
}

.page-info {
    flex: 1;
}

.page-info h4 {
    margin: 0 0 0.25rem 0;
}

.page-meta {
    font-size: 0.875rem;
    color: #6b7280;
}

.page-actions {
    display: flex;
    gap: 0.5rem;
}

.book-preview {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    margin-top: 1.5rem;
}

.book-preview h3 {
    margin: 0 0 1rem 0;
}

@media (max-width: 768px) {
    .edit-layout {
        grid-template-columns: 1fr;
    }
    
    .edit-sidebar {
        position: static;
    }
}

/* Sortable styles */
.sortable-ghost {
    opacity: 0.4;
    background: #f0f0f0;
}

.sortable-drag {
    opacity: 0.9;
    background: white;
    box-shadow: 0 5px 20px rgba(0,0,0,0.3);
}

.drag-handle {
    cursor: move;
}

.drag-handle:hover {
    color: var(--purple);
}

.reorder-status {
    padding: 0.5rem 1rem;
    background: #d1fae5;
    color: #065f46;
    border-radius: 6px;
    margin-top: 1rem;
    display: none;
}

.reorder-status.error {
    background: #fee2e2;
    color: #991b1b;
}

.reorder-status.show {
    display: block;
}
</style>

<!-- SortableJS -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const pagesList = document.getElementById('pages-list');
    
    if (pagesList) {
        // Initialize SortableJS
        const sortable = Sortable.create(pagesList, {
            handle: '.drag-handle',
            animation: 150,
            ghostClass: 'sortable-ghost',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                // Get the new order of page IDs
                const pageItems = pagesList.querySelectorAll('.page-item');
                const pageIds = Array.from(pageItems).map(item => item.dataset.id);
                
                // Send AJAX request to update order
                updatePageOrder(pageIds);
            }
        });
    }
    
    function updatePageOrder(pageIds) {
        const formData = new FormData();
        formData.append('_token', '<?= $auth->csrfToken() ?>');
        pageIds.forEach((id, index) => {
            formData.append('page_ids[]', id);
        });
        
        fetch('/books/<?= $book['id'] ?>/pages/reorder', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showStatus('Pages reordered successfully!', 'success');
            } else {
                showStatus('Error reordering pages. Please try again.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showStatus('Error reordering pages. Please try again.', 'error');
        });
    }
    
    function showStatus(message, type) {
        // Check if status element exists, if not create it
        let statusEl = document.querySelector('.reorder-status');
        if (!statusEl) {
            statusEl = document.createElement('div');
            statusEl.className = 'reorder-status';
            const pagesSection = document.querySelector('.pages-section');
            pagesSection.appendChild(statusEl);
        }
        
        statusEl.textContent = message;
        statusEl.className = 'reorder-status show';
        if (type === 'error') {
            statusEl.classList.add('error');
        }
        
        // Hide after 3 seconds
        setTimeout(() => {
            statusEl.classList.remove('show');
        }, 3000);
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>