<?php include __DIR__ . '/../partials/header.php'; ?>

<div class="container container-narrow">
    <div class="page-header">
        <a href="/books" class="btn-back">
            <svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M15 18l-6-6 6-6"/>
            </svg>
            Back to Library
        </a>
        <h1>Create New Book</h1>
    </div>

    <form method="POST" action="/books" enctype="multipart/form-data" class="book-form">
        <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
        
        <div class="form-group">
            <label for="title">Title *</label>
            <input type="text" id="title" name="title" required 
                   placeholder="Enter your book title"
                   value="<?= htmlspecialchars($_SESSION['old']['title'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="subtitle">Subtitle</label>
            <input type="text" id="subtitle" name="subtitle" 
                   placeholder="Optional subtitle"
                   value="<?= htmlspecialchars($_SESSION['old']['subtitle'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="author">Author</label>
            <input type="text" id="author" name="author" 
                   placeholder="Author name"
                   value="<?= htmlspecialchars($_SESSION['old']['author'] ?? '') ?>">
        </div>
        
        <div class="form-group">
            <label for="cover">Cover Image</label>
            <div class="file-upload">
                <input type="file" id="cover" name="cover" accept="image/jpeg,image/jpg,image/png,image/webp">
                <label for="cover" class="file-label">
                    <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="3" width="18" height="18" rx="2"/>
                        <circle cx="8.5" cy="8.5" r="1.5"/>
                        <path d="M21 15l-5-5L5 21"/>
                    </svg>
                    <span>Choose cover image</span>
                </label>
                <p class="help-text">JPG, PNG, or WebP. Max 5MB.</p>
            </div>
            <div id="cover-preview" class="cover-preview"></div>
        </div>
        
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="is_public" value="1">
                <span>Make this book public</span>
            </label>
            <p class="help-text">Public books can be viewed by anyone with the link</p>
        </div>
        
        <div class="form-actions">
            <a href="/books" class="btn btn-secondary">Cancel</a>
            <button type="submit" class="btn btn-primary">Create Book</button>
        </div>
    </form>
</div>

<style>
.container-narrow {
    max-width: 600px;
}

.page-header {
    margin-bottom: 2rem;
}

.btn-back {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    color: #6b7280;
    text-decoration: none;
    margin-bottom: 1rem;
    transition: color 0.2s;
}

.btn-back:hover {
    color: #374151;
}

.book-form {
    background: white;
    padding: 2rem;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.form-group {
    margin-bottom: 1.5rem;
}

.form-group label {
    display: block;
    margin-bottom: 0.5rem;
    font-weight: 500;
    color: #374151;
}

.form-group input[type="text"],
.form-group input[type="email"],
.form-group input[type="password"] {
    width: 100%;
    padding: 0.625rem 0.875rem;
    border: 1px solid #d1d5db;
    border-radius: 6px;
    font-size: 1rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}

.form-group input:focus {
    outline: none;
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.file-upload {
    position: relative;
}

.file-upload input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 0;
    height: 0;
}

.file-label {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.625rem 1rem;
    background: #f9fafb;
    border: 2px dashed #d1d5db;
    border-radius: 6px;
    cursor: pointer;
    transition: all 0.2s;
}

.file-label:hover {
    background: #f3f4f6;
    border-color: #9ca3af;
}

.file-upload input[type="file"]:focus + .file-label {
    border-color: #6366f1;
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.cover-preview {
    margin-top: 1rem;
}

.cover-preview img {
    max-width: 200px;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.checkbox-label {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    cursor: pointer;
}

.checkbox-label input[type="checkbox"] {
    width: 1.25rem;
    height: 1.25rem;
}

.help-text {
    margin-top: 0.25rem;
    font-size: 0.875rem;
    color: #6b7280;
}

.form-actions {
    display: flex;
    gap: 1rem;
    justify-content: flex-end;
    margin-top: 2rem;
}

.btn-secondary {
    background: #f3f4f6;
    color: #374151;
}

.btn-secondary:hover {
    background: #e5e7eb;
}
</style>

<script>
// Preview cover image
document.getElementById('cover').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('cover-preview');
    
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = '<img src="' + e.target.result + '" alt="Cover preview">';
        };
        reader.readAsDataURL(file);
    } else {
        preview.innerHTML = '';
    }
});

// Update file label text
document.getElementById('cover').addEventListener('change', function(e) {
    const label = this.nextElementSibling.querySelector('span');
    const fileName = e.target.files[0]?.name;
    label.textContent = fileName || 'Choose cover image';
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>
<?php unset($_SESSION['old']); ?>