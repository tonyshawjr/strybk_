<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="page-header">
        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn-back">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Back to <?= htmlspecialchars($book['title']) ?>
        </a>
        <h1>Edit Page</h1>
    </div>

    <form method="POST" action="/pages/<?= $page['id'] ?>/update" enctype="multipart/form-data" class="page-form">
        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
        
        <div class="form-group">
            <label for="kind">Page Type</label>
            <select id="kind" name="kind" class="page-type-selector">
                <option value="chapter" <?= $page['kind'] === 'chapter' ? 'selected' : '' ?>>Chapter</option>
                <option value="section" <?= $page['kind'] === 'section' ? 'selected' : '' ?>>Section</option>
                <option value="picture" <?= $page['kind'] === 'picture' ? 'selected' : '' ?>>Picture</option>
                <option value="divider" <?= $page['kind'] === 'divider' ? 'selected' : '' ?>>Divider</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="title">Title <span class="required">*</span></label>
            <input type="text" id="title" name="title" value="<?= htmlspecialchars($page['title']) ?>" required>
        </div>
        
        <div class="form-group">
            <label for="position">Position</label>
            <input type="number" id="position" name="position" value="<?= ($page['order_index'] ?? 0) + 1 ?>" min="1">
            <p class="help-text">Order of this page in the book</p>
        </div>
        
        <div class="form-group content-group" id="content-group" <?= in_array($page['kind'], ['picture', 'divider']) ? 'style="display: none;"' : '' ?>>
            <label for="content">Content</label>
            <textarea id="content" name="content" rows="20"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
            <div class="editor-toolbar">
                <span class="word-count" id="word-count"><?= $page['word_count'] ?> words</span>
                <span class="char-count" id="char-count">0 characters</span>
                <span class="last-saved">Last saved: <?= format_date($page['updated_at'] ?? $page['created_at']) ?></span>
            </div>
        </div>
        
        <div class="form-group picture-group" id="picture-group" <?= $page['kind'] !== 'picture' ? 'style="display: none;"' : '' ?>>
            <?php if ($page['kind'] === 'picture' && $page['content']): ?>
                <div class="current-image">
                    <img src="<?= htmlspecialchars($page['content']) ?>" alt="Current image">
                </div>
            <?php endif; ?>
            <label for="image">Image</label>
            <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/webp">
            <p class="help-text">Upload a new image to replace the current one (JPG, PNG, or WebP, max 10MB)</p>
        </div>
        
        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Update Page</button>
            <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="btn btn-secondary">Cancel</a>
            
            <form method="POST" action="/pages/<?= $page['id'] ?>/delete" class="delete-form" 
                  onsubmit="return confirm('Are you sure you want to delete this page?')">
                <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                <button type="submit" class="btn btn-danger">Delete Page</button>
            </form>
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
.form-group input[type="number"],
.form-group select,
.form-group textarea {
    width: 100%;
    padding: 0.625rem;
    border: 1px solid var(--gray-300);
    border-radius: 6px;
    font-size: 1rem;
}

.form-group input[type="text"]:focus,
.form-group input[type="number"]:focus,
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

.current-image {
    margin-bottom: 1rem;
    padding: 1rem;
    background: var(--gray-50);
    border-radius: 6px;
}

.current-image img {
    max-width: 100%;
    height: auto;
    max-height: 400px;
    display: block;
    margin: 0 auto;
    border-radius: 4px;
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

.last-saved {
    color: var(--gray-500);
    font-style: italic;
}

.form-actions {
    display: flex;
    gap: 1rem;
    margin-top: 2rem;
}

.delete-form {
    margin-left: auto;
}

.btn-danger {
    background: #dc2626;
    color: white;
}

.btn-danger:hover {
    background: #b91c1c;
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
    const contentTextarea = document.getElementById('content');
    
    // Initialize SimpleMDE
    let simplemde = null;
    
    function initializeEditor() {
        if (simplemde || !contentTextarea) return;
        
        simplemde = new SimpleMDE({
            element: contentTextarea,
            spellChecker: false,
            status: false,
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
                uniqueId: 'strybk-page-<?= $page['id'] ?>',
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
    
    // Initialize editor if content is visible
    const currentKind = kindSelector.value;
    if (currentKind === 'chapter' || currentKind === 'section') {
        initializeEditor();
    }
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>