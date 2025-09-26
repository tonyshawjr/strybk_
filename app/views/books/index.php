<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">

    <div class="books-grid">
        <?php foreach ($books as $book): ?>
            <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="book-card-link">
                <div class="book-card">
                    <div class="book-cover" style="<?= $book['cover_path'] ? '' : 'background: ' . getBookColor($book['title']) ?>">
                        <?php if ($book['cover_path']): ?>
                            <img src="<?= htmlspecialchars($book['cover_path']) ?>" alt="<?= htmlspecialchars($book['title']) ?>">
                        <?php else: ?>
                            <div class="book-cover-content">
                                <h2 class="book-cover-title"><?= htmlspecialchars($book['title']) ?></h2>
                                <?php if ($book['subtitle']): ?>
                                    <p class="book-cover-subtitle"><?= htmlspecialchars($book['subtitle']) ?></p>
                                <?php endif; ?>
                                <?php if ($book['author']): ?>
                                    <div class="book-cover-author"><?= htmlspecialchars($book['author']) ?></div>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="book-meta">
                        <h3 class="book-title"><?= htmlspecialchars($book['title']) ?></h3>
                        <?php if ($book['author']): ?>
                            <p class="book-author"><?= htmlspecialchars($book['author']) ?></p>
                        <?php else: ?>
                            <p class="book-pages"><?= $book['page_count'] ?> pages</p>
                        <?php endif; ?>
                    </div>
                </div>
            </a>
        <?php endforeach; ?>
        
        <!-- Add New Book Card -->
        <a href="/books/new" class="book-card-link add-book-card">
            <div class="book-card">
                <div class="book-cover add-book-cover">
                    <div class="add-book-icon">
                        <div class="icon-circle">
                            <i class="fa-solid fa-plus"></i>
                        </div>
                    </div>
                </div>
                <div class="book-meta">
                    <h3 class="book-title">Create New Book</h3>
                    <p class="book-author">Start writing</p>
                </div>
            </div>
        </a>
    </div>
</div>

<?php
// Generate consistent colors for book covers based on title
function getBookColor($title) {
    $colors = [
        'linear-gradient(135deg, #111111 0%, #333333 100%)', // Black to dark gray
        'linear-gradient(135deg, #333333 0%, #666666 100%)', // Dark gray to medium gray
        'linear-gradient(135deg, #666666 0%, #999999 100%)', // Medium gray to light gray
        'linear-gradient(135deg, #999999 0%, #CCCCCC 100%)', // Light gray to lighter gray
        'linear-gradient(135deg, #111111 0%, #666666 100%)', // Black to medium gray
        'linear-gradient(135deg, #333333 0%, #999999 100%)', // Dark to light gray
        '#111111', // Black
        '#333333', // Dark gray
        '#666666', // Medium gray
        '#999999', // Light gray
    ];
    
    // Use title hash to consistently select same color
    $hash = crc32($title);
    $index = abs($hash) % count($colors);
    return $colors[$index];
}
?>

<style>
.books-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 48px;
    margin-bottom: 80px;
    padding-top: 20px;
}

.book-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
}

.book-card {
    cursor: pointer;
    transition: transform 0.2s ease;
}

.book-card:hover {
    transform: translateY(-4px);
}

.book-cover {
    width: 100%;
    aspect-ratio: 3/4;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1), 0 4px 24px rgba(0,0,0,0.08);
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.book-card:hover .book-cover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15), 0 8px 32px rgba(0,0,0,0.12);
}

.book-cover img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.book-cover-content {
    padding: 2rem;
    color: white;
    text-align: center;
    width: 100%;
}

.book-cover-title {
    font-size: 1.75rem;
    font-weight: 700;
    margin: 0 0 1rem 0;
    line-height: 1.2;
    color: white;
}

.book-cover-subtitle {
    font-size: 1rem;
    opacity: 0.9;
    margin: 0 0 2rem 0;
    font-style: italic;
}

.book-cover-author {
    position: absolute;
    bottom: 2rem;
    left: 50%;
    transform: translateX(-50%);
    font-size: 0.875rem;
    opacity: 0.9;
    white-space: nowrap;
}

.book-meta {
    margin-top: 16px;
    text-align: center;
}

.book-title {
    font-size: 16px;
    font-weight: 600;
    color: #111111;
    margin: 0 0 4px 0;
    line-height: 1.4;
}

.book-author {
    font-size: 14px;
    color: #666666;
    margin: 0;
}

.book-pages {
    font-size: 14px;
    color: #999999;
    margin: 0;
}

/* Add New Book Card Styles */
.add-book-cover {
    background: #FAFAFA;
    border: 2px dashed #E5E5E5;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    box-shadow: none;
}

.add-book-card:hover .add-book-cover {
    border-color: #111111;
    background: #F5F5F5;
    transform: translateY(-2px);
}

.add-book-icon {
    display: flex;
    align-items: center;
    justify-content: center;
}

.icon-circle {
    width: 60px;
    height: 60px;
    background: #111111;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}

.icon-circle i {
    color: white;
    font-size: 24px;
}

.add-book-card:hover .icon-circle {
    background: #333333;
    transform: scale(1.1);
}

.add-book-card:hover .book-title {
    color: #111111;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 2rem;
    }
    
    .book-cover-title {
        font-size: 1.25rem;
    }
    
    .book-cover-content {
        padding: 1.5rem;
    }
    
    .book-title {
        font-size: 1rem;
    }
    
    .book-author {
        font-size: 0.813rem;
    }
}

/* Tablet */
@media (min-width: 768px) and (max-width: 1024px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
    }
}

/* Large screens */
@media (min-width: 1280px) {
    .books-grid {
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 4rem;
    }
}
</style>

<?php include __DIR__ . '/../partials/footer.php'; ?>