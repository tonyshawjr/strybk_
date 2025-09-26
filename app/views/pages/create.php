<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <nav class="breadcrumb">
        <a href="/dashboard" class="breadcrumb-item">Dashboard</a>
        <span class="breadcrumb-separator">›</span>
        <a href="/books" class="breadcrumb-item">Books</a>
        <span class="breadcrumb-separator">›</span>
        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="breadcrumb-item"><?= htmlspecialchars($book['title']) ?></a>
        <span class="breadcrumb-separator">›</span>
        <span class="breadcrumb-current">New Page</span>
    </nav>
    
    <div class="page-header">
        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn-back">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Back to <?= htmlspecialchars($book['title']) ?>
        </a>
        <h1>Add New Page</h1>
    </div>

    <form method="POST" action="/books/<?= $book['id'] ?>/pages/store" enctype="multipart/form-data" class="page-form">
        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
        <input type="hidden" name="position" value="<?= $next_position ?>">
        
        <div class="form-group">
            <label for="kind">Page Type</label>
            <select id="kind" name="kind" class="page-type-selector">
                <option value="chapter" selected>Chapter</option>
                <option value="section">Section</option>
                <option value="picture">Picture</option>
                <option value="divider">Divider</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="title">Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" required autofocus>
        </div>
        
        <div class="form-group content-group" id="content-group">
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="20"></textarea>
            <div class="editor-toolbar">
                <span class="word-count" id="word-count">0 words</span>
                <span class="char-count" id="char-count">0 characters</span>
            </div>
        </div>
        
        <div class="form-group picture-group" id="picture-group" style="display: none;">
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/webp">
            <p class="help-text">Upload an image for this picture page (JPG, PNG, or WebP, max 10MB)</p>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Create Page</button>
            <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<!-- SimpleMDE CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

<!-- SimpleMDE JS -->
<script src="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.js"></script>

<style>
.page-form {
    max-width: 900px;
    margin: 2rem auto;
}

.breadcrumb {
    display: flex;
    align-items: center;
    font-size: 0.875rem;
    color: var(--gray-600);
    margin-bottom: 1rem;
}

.breadcrumb-item {
    color: var(--gray-600);
    text-decoration: none;
    transition: color 0.2s;
}

.breadcrumb-item:hover {
    color: var(--purple);
}

.breadcrumb-separator {
    margin: 0 0.5rem;
    color: var(--gray-400);
}

.breadcrumb-current {
    color: var(--gray-900);
    font-weight: 500;
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    flex-wrap: wrap;
    gap: 1rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: var(--gray-600);
    text-decoration: none;
    transition: color 0.2s;
}

.btn-back:hover {
    color: var(--purple);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 600;
    color: var(--gray-700);
}

.form-group input[type="text"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--gray-300);
    border-radius: 6px;
    font-size: 1rem;
}

.form-group input[type="text"]:focus,
.form-group select:focus,
.form-group textarea:focus {
    outline: none;
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(108, 74, 182, 0.1);
}

.required {
    color: #dc2626;
}

.help-text {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: var(--gray-500);
}

.editor-toolbar {
    display: flex;
    justify-content: space-between;
    padding: 0.5rem;
    background: var(--gray-50);
    border: 1px solid var(--gray-300);
    border-top: none;
    border-radius: 0 0 6px 6px;
    font-size: 0.875rem;
    color: var(--gray-600);
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

/* SimpleMDE Overrides */
.CodeMirror {
    border-radius: 6px 6px 0 0;
    border-color: var(--gray-300);
}

.CodeMirror-focused {
    border-color: var(--purple);
    box-shadow: 0 0 0 3px rgba(108, 74, 182, 0.1);
}

.editor-toolbar.fullscreen {
    z-index: 10;
}

.CodeMirror-fullscreen {
    z-index: 9;
}

.editor-toolbar {
    border-color: var(--gray-300);
    border-radius: 6px 6px 0 0;
}

.editor-preview {
    background: white;
}

.editor-preview-side {
    border-left: 1px solid var(--gray-300);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kindSelector = document.getElementById('kind');
    const contentGroup = document.getElementById('content-group');
    const pictureGroup = document.getElementById('picture-group');
    const titleInput = document.getElementById('title');
    
    // Initialize SimpleMDE
    let simplemde = null;
    
    function initializeEditor() {
        if (simplemde) return;
        
        simplemde = new SimpleMDE({
            element: document.getElementById('content'),
            spellChecker: false,
            status: false, // We'll use our own status bar
            toolbar: [
                'bold', 'italic', 'heading', '|',
                'quote', 'unordered-list', 'ordered-list', '|',
                'link', 'image', '|',
                'preview', 'side-by-side', 'fullscreen', '|',
                'guide'
            ],
            placeholder: 'Start writing your content here...',
            autosave: {
                enabled: true,
                uniqueId: 'strybk-page-' + Date.now(),
                delay: 1000,
            }
        });
        
        // Update word count
        function updateCounts() {
            const content = simplemde.value();
            const plainText = content.replace(/[#*_\[\]()>`~-]+/g, '');
            const words = plainText.trim().split(/\s+/).filter(word => word.length > 0).length;
            const chars = plainText.length;
            
            document.getElementById('word-count').textContent = words + ' words';
            document.getElementById('char-count').textContent = chars + ' characters';
        }
        
        simplemde.codemirror.on('change', updateCounts);
        updateCounts();
    }
    
    // Handle page type change
    kindSelector.addEventListener('change', function() {
        const kind = this.value;
        
        if (kind === 'picture') {
            contentGroup.style.display = 'none';
            pictureGroup.style.display = 'block';
            if (simplemde) {
                simplemde.toTextArea();
                simplemde = null;
            }
        } else if (kind === 'divider') {
            contentGroup.style.display = 'none';
            pictureGroup.style.display = 'none';
            if (simplemde) {
                simplemde.toTextArea();
                simplemde = null;
            }
        } else {
            contentGroup.style.display = 'block';
            pictureGroup.style.display = 'none';
            initializeEditor();
        }
        
        // Update title placeholder
        if (kind === 'chapter') {
            titleInput.placeholder = 'Chapter title';
        } else if (kind === 'section') {
            titleInput.placeholder = 'Section title';
        } else if (kind === 'picture') {
            titleInput.placeholder = 'Image caption';
        } else if (kind === 'divider') {
            titleInput.placeholder = 'Divider text (optional)';
        }
    });
    
    // Initialize editor for default chapter type
    initializeEditor();
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>