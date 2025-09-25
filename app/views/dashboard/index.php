<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="dashboard-header">
        <h1>Welcome back, <?= htmlspecialchars($auth->user()['name']) ?>!</h1>
        <a href="/books/new" class="btn btn-primary">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M12 5v14M5 12h14"/>
            </svg>
            Create New Book
        </a>
    </div>
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value"><?= $stats['total_books'] ?></div>
            <div class="stat-label">Total Books</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= $stats['public_books'] ?></div>
            <div class="stat-label">Public Books</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total_pages']) ?></div>
            <div class="stat-label">Total Pages</div>
        </div>
        <div class="stat-card">
            <div class="stat-value"><?= number_format($stats['total_words']) ?></div>
            <div class="stat-label">Total Words</div>
        </div>
    </div>
    
    <div class="recent-section">
        <div class="section-header">
            <h2>Recent Books</h2>
            <a href="/books" class="link">View all →</a>
        </div>
        
        <?php if (empty($recent_books)): ?>
            <div class="empty-state">
                <svg class="empty-icon" width="64" height="64" fill="none" stroke="currentColor" stroke-width="1.5">
                    <rect x="8" y="8" width="48" height="48" rx="4"/>
                    <path d="M32 24v16M24 32h16"/>
                </svg>
                <h3>No books yet</h3>
                <p>Start your writing journey by creating your first book</p>
                <a href="/books/new" class="btn btn-primary">Create Your First Book</a>
            </div>
        <?php else: ?>
            <div class="books-list">
                <?php foreach ($recent_books as $book): ?>
                    <div class="book-row">
                        <div class="book-main">
                            <?php if ($book['cover_path']): ?>
                                <img src="<?= htmlspecialchars($book['cover_path']) ?>" 
                                     alt="Cover" class="book-thumbnail">
                            <?php else: ?>
                                <div class="book-thumbnail book-thumbnail-placeholder">
                                    <?= htmlspecialchars($book['title'][0] ?? 'B') ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="book-details">
                                <h3><?= htmlspecialchars($book['title']) ?></h3>
                                <?php if ($book['author']): ?>
                                    <p class="book-author">by <?= htmlspecialchars($book['author']) ?></p>
                                <?php endif; ?>
                                <div class="book-stats">
                                    <span><?= $book['page_count'] ?> pages</span>
                                    <span>•</span>
                                    <span><?= number_format($book['word_count']) ?> words</span>
                                    <?php if ($book['is_public']): ?>
                                        <span>•</span>
                                        <span class="badge-public">Public</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="book-actions">
                            <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn btn-sm">Edit</a>
                            <a href="/read/<?= htmlspecialchars($book['slug']) ?>" class="btn btn-sm btn-ghost">Read</a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 3rem;
}

.stat-card {
    background: white;
    padding: 1.5rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    color: #111827;
    margin-bottom: 0.5rem;
}

.stat-label {
    color: #6b7280;
    font-size: 0.875rem;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.recent-section {
    background: white;
    padding: 2rem;
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

.link {
    color: #6366f1;
    text-decoration: none;
}

.link:hover {
    text-decoration: underline;
}

.books-list {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.book-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #f9fafb;
    border-radius: 6px;
    transition: background 0.2s;
}

.book-row:hover {
    background: #f3f4f6;
}

.book-main {
    display: flex;
    align-items: center;
    gap: 1rem;
    flex: 1;
}

.book-thumbnail {
    width: 60px;
    height: 80px;
    object-fit: cover;
    border-radius: 4px;
    flex-shrink: 0;
}

.book-thumbnail-placeholder {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    font-weight: bold;
}

.book-details h3 {
    margin: 0 0 0.25rem 0;
    font-size: 1.125rem;
}

.book-author {
    margin: 0.25rem 0;
    color: #6b7280;
    font-size: 0.875rem;
}

.book-stats {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.badge-public {
    background: #d1fae5;
    color: #065f46;
    padding: 0.125rem 0.5rem;
    border-radius: 10px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.book-actions {
    display: flex;
    gap: 0.5rem;
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-icon {
    color: #e5e7eb;
    margin-bottom: 1rem;
}

.empty-state h3 {
    color: #374151;
    margin: 1rem 0;
}

.empty-state p {
    color: #6b7280;
    margin-bottom: 2rem;
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