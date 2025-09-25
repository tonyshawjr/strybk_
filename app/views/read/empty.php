<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title) ?> - Strybk</title>
    <style>
        :root {
            --purple: #6C4AB6;
            --gray-50: #f9fafb;
            --gray-600: #4b5563;
            --gray-900: #111827;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Georgia', serif;
            background: var(--gray-50);
            color: var(--gray-900);
            line-height: 1.8;
            display: flex;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .empty-container {
            text-align: center;
            max-width: 600px;
        }
        
        .book-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }
        
        .book-author {
            color: var(--gray-600);
            margin-bottom: 3rem;
        }
        
        .empty-message {
            font-size: 1.25rem;
            color: var(--gray-600);
            margin-bottom: 2rem;
        }
        
        .btn {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: var(--purple);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: opacity 0.2s;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
    </style>
</head>
<body>
    <div class="empty-container">
        <h1 class="book-title"><?= htmlspecialchars($book['title']) ?></h1>
        <?php if ($book['author']): ?>
            <div class="book-author">by <?= htmlspecialchars($book['author']) ?></div>
        <?php endif; ?>
        
        <p class="empty-message">This book doesn't have any pages yet.</p>
        
        <?php if ($auth->check() && $book['created_by'] == $auth->user()['id']): ?>
            <a href="/books/<?= $book['id'] ?>/pages/new" class="btn">Add First Page</a>
        <?php endif; ?>
    </div>
</body>
</html>