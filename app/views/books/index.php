<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="library-header">
        <h1>My Books</h1>
        <a href="/books/new" class="btn btn-primary">
            <svg class="icon" width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            New Book
        </a>
    </div>

    <?php if (empty($books)): ?>
        <div class="empty-state">
            <svg class="empty-icon" width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5">
                <rect x="8" y="8" width="48" height="48" rx="4"/>
                <path d="M32 24v16M24 32h16"/>
            </svg>
            <h2>No books yet</h2>
            <p>Create your first book to get started</p>
            <a href="/books/new" class="btn btn-primary">Create Your First Book</a>
        </div>
    <?php else: ?>
        <div class="books-grid">
            <?php foreach ($books as $book): ?>
                <div class="book-card">
                    <?php if ($book['cover_path']): ?>
                        <div class="book-cover">
                            <img src="<?= htmlspecialchars($book['cover_path']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        </div>
                    <?php else: ?>
                        <div class="book-cover book-cover-placeholder">
                            <div class="placeholder-text"><?= htmlspecialchars($book['title'][0] ?? 'B') ?></div>
                        </div>
                    <?php endif; ?>
                    
                    <div class="book-info">
                        <h3><?= htmlspecialchars($book['title']) ?></h3>
                        <?php if ($book['subtitle']): ?>
                            <p class="book-subtitle"><?= htmlspecialchars($book['subtitle']) ?></p>
                        <?php endif; ?>
                        <?php if ($book['author']): ?>
                            <p class="book-author">by <?= htmlspecialchars($book['author']) ?></p>
                        <?php endif; ?>
                        
                        <div class="book-stats">
                            <span><?= $book['page_count'] ?> pages</span>
                            <span><?= number_format($book['word_count']) ?> words</span>
                        </div>
                        
                        <div class="book-status">
                            <?php if ($book['is_public']): ?>
                                <span class="badge badge-public">Public</span>
                            <?php else: ?>
                                <span class="badge badge-private">Private</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="book-actions">
                        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn btn-sm">Edit</a>
                        <a href="/read/<?= htmlspecialchars($book['slug']) ?>" class="btn btn-sm">Read</a>
                        <form method="POST" action="/books/<?= $book['id'] ?>/visibility" class="inline-form">
                            <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">
                            <button type="submit" class="btn btn-sm btn-ghost">
                                <?= $book['is_public'] ? 'Make Private' : 'Make Public' ?>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<style>
.library-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 2rem;
}

.book-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.2s, box-shadow 0.2s;
}

.book-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.book-cover {
    width: 100%;
    height: 300px;
    background: #f5f5f5;
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-cover-placeholder {
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.placeholder-text {
    font-size: 4rem;
    font-weight: bold;
}

.book-info {
    padding: 1rem;
}

.book-info h3 {
    margin: 0 0 0.5rem 0;
    font-size: 1.125rem;
}

.book-subtitle {
    color: #666;
    font-size: 0.875rem;
    margin: 0.25rem 0;
}

.book-author {
    color: #888;
    font-size: 0.875rem;
    margin: 0.5rem 0;
}

.book-stats {
    display: flex;
    gap: 1rem;
    margin: 1rem 0;
    font-size: 0.875rem;
    color: #666;
}

.book-status {
    margin: 1rem 0;
}

.badge {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.badge-public {
    background: #d1fae5;
    color: #065f46;
}

.badge-private {
    background: #fee2e2;
    color: #991b1b;
}

.book-actions {
    padding: 0 1rem 1rem;
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 4rem 2rem;
}

.empty-icon {
    color: #e5e7eb;
    margin-bottom: 1rem;
}

.empty-state h2 {
    color: #374151;
    margin: 1rem 0;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
}

.inline-form {
    display: inline;
}

.btn-ghost {
    background: transparent;
    color: #6b7280;
}

.btn-ghost:hover {
    background: #f3f4f6;
}
</style>

<?php include __DIR__ . '/../partials/footer.php'; ?>