<?php 
$showBackButton = true;
$backButtonUrl = '/books/' . htmlspecialchars($book['slug']) . '/edit';
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="breadcrumb-link">
            <?= htmlspecialchars($book['title']) ?>
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current">New Page</span>
    </div>
    
    <div class="editor-container">
        <!-- Editor Toolbar -->
        <div class="editor-toolbar">
            <div class="editor-toolbar-inner">
                <div class="toolbar-group">
                    <select id="kind" name="kind" class="page-type-selector">
                        <option value="chapter" selected>Chapter</option>
                        <option value="section">Section</option>
                        <option value="picture">Picture</option>
                        <option value="divider">Divider</option>
                    </select>
                </div>
                
                <div class="toolbar-divider"></div>
                
                <div class="toolbar-group format-tools">
                    <button class="toolbar-btn" data-command="bold" title="Bold">
                        <i class="fa-solid fa-bold"></i>
                    </button>
                    <button class="toolbar-btn" data-command="italic" title="Italic">
                        <i class="fa-solid fa-italic"></i>
                    </button>
                    <button class="toolbar-btn" data-command="quote" title="Quote">
                        <i class="fa-solid fa-quote-left"></i>
                    </button>
                </div>
                
                <div class="toolbar-divider format-tools"></div>
                
                <div class="toolbar-group format-tools">
                    <button class="toolbar-btn" data-command="code" title="Code">
                        <i class="fa-solid fa-code"></i>
                    </button>
                    <button class="toolbar-btn" data-command="chevrons" title="Chevrons">
                        <i class="fa-solid fa-chevron-right"></i>
                    </button>
                    <button class="toolbar-btn" data-command="link" title="Link">
                        <i class="fa-solid fa-link"></i>
                    </button>
                </div>
                
                <div class="toolbar-divider format-tools"></div>
                
                <div class="toolbar-group format-tools">
                    <button class="toolbar-btn" data-command="bullet" title="Bullet list">
                        <i class="fa-solid fa-list-ul"></i>
                    </button>
                    <button class="toolbar-btn" data-command="number" title="Numbered list">
                        <i class="fa-solid fa-list-ol"></i>
                    </button>
                    <button class="toolbar-btn" data-command="image" title="Insert image">
                        <i class="fa-regular fa-image"></i>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Editor Content Area -->
        <div class="editor-content">
            <form method="POST" action="/books/<?= $book['id'] ?>/pages/store" enctype="multipart/form-data" id="page-form">
                <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                <input type="hidden" name="position" value="<?= $next_position ?>">
                <input type="hidden" id="kind-hidden" name="kind" value="chapter">
                
                <!-- Title Input -->
                <div class="editor-title">
                    <input type="text" 
                           id="title"
                           name="title" 
                           class="title-input" 
                           placeholder="Page title..." 
                           required
                           autofocus>
                </div>
                
                <!-- Content Editor -->
                <div class="editor-body" id="content-group">
                    <div class="editor-wrapper">
                        <div id="editor" class="content-editor" contenteditable="true">
                            <p><br></p>
                        </div>
                        <textarea name="content" id="content-textarea" style="display: none;"></textarea>
                    </div>
                </div>
                
                <!-- Picture Upload -->
                <div class="editor-body picture-group" id="picture-group" style="display: none;">
                    <div class="upload-area">
                        <input type="file" id="image" name="image" accept="image/jpeg,image/jpg,image/png,image/webp">
                        <label for="image" class="upload-label">
                            <i class="fa-regular fa-image"></i>
                            <span>Click to upload an image</span>
                            <small>JPG, PNG, or WebP (max 10MB)</small>
                        </label>
                        <div id="image-preview" class="image-preview" style="display: none;">
                            <img src="" alt="Preview">
                        </div>
                    </div>
                </div>
                
                <!-- Editor Footer -->
                <div class="editor-footer">
                    <div class="footer-left">
                        <div class="word-count" id="word-count-display">
                            <span id="word-count">0</span> words
                        </div>
                    </div>
                    <div class="editor-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/books/<?= htmlspecialchars($book['slug']) ?>/edit'">Cancel</button>
                        <button type="submit" class="btn-primary">Create Page</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<style>
/* Breadcrumb Navigation */
.breadcrumb-nav {
    max-width: calc(700px + 120px);
    margin: 0 auto 24px;
    padding: 0 60px;
    font-size: 14px;
    color: #999999;
}

.breadcrumb-link {
    color: #666666;
    text-decoration: none;
    transition: color 0.2s ease;
}

.breadcrumb-link:hover {
    color: #111111;
}

.breadcrumb-separator {
    margin: 0 8px;
    color: #CCCCCC;
}

.breadcrumb-current {
    color: #111111;
    font-weight: 500;
}

/* Editor Container */
.editor-container {
    background: white;
    border-radius: 12px;
    margin-bottom: 40px;
}

/* Editor Toolbar */
.editor-toolbar {
    background: white;
    background: rgba(255, 255, 255, 0.98);
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    padding: 8px 0;
    border-bottom: 1px solid #F0F0F0;
    border-radius: 12px 12px 0 0;
    position: -webkit-sticky;
    position: sticky;
    top: 0px;
    z-index: 100;
}

.editor-toolbar-inner {
    max-width: calc(700px + 120px);
    margin: 0 auto;
    padding: 0 60px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 4px;
}

.page-type-selector {
    padding: 6px 12px;
    border: 1px solid #E5E5E5;
    border-radius: 6px;
    background: white;
    color: #111111;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
}

.page-type-selector:hover {
    border-color: #111111;
}

.page-type-selector:focus {
    outline: none;
    border-color: #111111;
    box-shadow: 0 0 0 3px rgba(17, 17, 17, 0.1);
}

.toolbar-btn {
    width: 36px;
    height: 36px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: transparent;
    border: none;
    border-radius: 6px;
    color: #666666;
    cursor: pointer;
    transition: all 0.2s ease;
    font-size: 16px;
}

.toolbar-btn:hover {
    background: white;
    color: #111111;
    box-shadow: 0 1px 3px rgba(0,0,0,0.1);
}

.toolbar-divider {
    width: 1px;
    height: 24px;
    background: #D5D5D5;
}

/* Hide format tools for certain page types */
.format-tools {
    transition: opacity 0.3s ease;
}

.format-tools.hidden {
    opacity: 0;
    pointer-events: none;
    display: none;
}

/* Editor Content */
.editor-content {
    padding: 0;
}

/* Title Input */
.editor-title {
    padding: 40px 60px 20px;
    max-width: calc(700px + 120px);
    margin: 0 auto;
}

.title-input {
    width: 100%;
    font-size: 36px;
    font-weight: 700;
    color: #111111;
    border: none;
    outline: none;
    padding: 0;
    font-family: 'Inter', sans-serif;
    background: transparent;
}

.title-input::placeholder {
    color: #DDDDDD;
}

/* Content Editor */
.editor-body {
    padding: 0 60px 60px;
    min-height: 400px;
    max-width: calc(700px + 120px);
    margin: 0 auto;
}

.editor-wrapper {
    max-width: 700px;
    margin: 0;
}

.content-editor {
    font-family: 'Georgia', serif;
    font-size: 19px;
    line-height: 1.8;
    color: #111111;
    min-height: 300px;
    outline: none;
}

.content-editor:focus {
    outline: none;
}

/* Picture Upload */
.picture-group {
    display: none;
}

.upload-area {
    position: relative;
}

.upload-area input[type="file"] {
    position: absolute;
    opacity: 0;
    width: 100%;
    height: 200px;
    cursor: pointer;
}

.upload-label {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 12px;
    padding: 48px;
    border: 2px dashed #E5E5E5;
    border-radius: 8px;
    background: #FAFAFA;
    color: #999999;
    cursor: pointer;
    transition: all 0.2s ease;
}

.upload-label:hover {
    border-color: #111111;
    background: white;
    color: #111111;
}

.upload-label i {
    font-size: 48px;
}

.upload-label span {
    font-size: 16px;
    font-weight: 500;
}

.upload-label small {
    font-size: 14px;
    color: #999999;
}

.image-preview {
    margin-top: 20px;
    text-align: center;
}

.image-preview img {
    max-width: 100%;
    max-height: 400px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

/* Editor Footer */
.editor-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 60px;
    background: white;
    border-top: 1px solid #F5F5F5;
    border-radius: 0 0 12px 12px;
}

.footer-left {
    display: flex;
    flex-direction: column;
}

.word-count {
    font-size: 14px;
    color: #999999;
}

.editor-actions {
    display: flex;
    gap: 12px;
}

.btn-primary,
.btn-secondary {
    padding: 8px 20px;
    border-radius: 6px;
    font-size: 14px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    border: 1px solid transparent;
}

.btn-primary {
    background: #111111;
    color: white;
    border-color: #111111;
}

.btn-primary:hover {
    background: #333333;
    border-color: #333333;
}

.btn-secondary {
    background: white;
    color: #666666;
    border-color: #E5E5E5;
}

.btn-secondary:hover {
    background: #F5F5F5;
    color: #111111;
    border-color: #111111;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .breadcrumb-nav,
    .editor-toolbar-inner,
    .editor-title,
    .editor-body {
        padding-left: 20px;
        padding-right: 20px;
    }
    
    .toolbar-btn {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .title-input {
        font-size: 24px;
    }
    
    .content-editor {
        font-size: 16px;
    }
    
    .editor-footer {
        padding: 12px 20px;
        flex-direction: column;
        gap: 16px;
    }
    
    .editor-actions {
        width: 100%;
    }
    
    .btn-primary,
    .btn-secondary {
        flex: 1;
    }
}
</style>

<script>
// Update word count
function updateWordCount() {
    const editor = document.getElementById('editor');
    const text = editor.innerText || editor.textContent || '';
    const words = text.trim().split(/\s+/).filter(word => word.length > 0).length;
    document.getElementById('word-count').textContent = words;
}

document.addEventListener('DOMContentLoaded', function() {
    const kindSelector = document.getElementById('kind');
    const kindHidden = document.getElementById('kind-hidden');
    const contentGroup = document.getElementById('content-group');
    const pictureGroup = document.getElementById('picture-group');
    const titleInput = document.getElementById('title');
    const editor = document.getElementById('editor');
    const formatTools = document.querySelectorAll('.format-tools');
    
    // Initialize word count
    updateWordCount();
    
    // Update word count on input
    editor.addEventListener('input', function() {
        updateWordCount();
        
        // Ensure we always have at least one paragraph
        if (!editor.innerHTML.trim()) {
            editor.innerHTML = '<p><br></p>';
        }
    });
    
    // Handle page type change
    kindSelector.addEventListener('change', function() {
        const kind = this.value;
        kindHidden.value = kind;
        
        if (kind === 'picture') {
            contentGroup.style.display = 'none';
            pictureGroup.style.display = 'block';
            formatTools.forEach(tool => tool.classList.add('hidden'));
        } else if (kind === 'divider') {
            contentGroup.style.display = 'none';
            pictureGroup.style.display = 'none';
            formatTools.forEach(tool => tool.classList.add('hidden'));
        } else {
            contentGroup.style.display = 'block';
            pictureGroup.style.display = 'none';
            formatTools.forEach(tool => tool.classList.remove('hidden'));
        }
        
        // Update title placeholder
        if (kind === 'chapter') {
            titleInput.placeholder = 'Chapter title...';
        } else if (kind === 'section') {
            titleInput.placeholder = 'Section title...';
        } else if (kind === 'picture') {
            titleInput.placeholder = 'Image caption...';
        } else if (kind === 'divider') {
            titleInput.placeholder = 'Divider text (optional)...';
        }
    });
    
    // Handle image preview
    const imageInput = document.getElementById('image');
    const imagePreview = document.getElementById('image-preview');
    
    imageInput?.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                imagePreview.querySelector('img').src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });
    
    // Toolbar button handlers
    document.querySelectorAll('.toolbar-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const command = this.dataset.command;
            
            switch(command) {
                case 'bold':
                    document.execCommand('bold', false, null);
                    break;
                case 'italic':
                    document.execCommand('italic', false, null);
                    break;
                case 'quote':
                    document.execCommand('formatBlock', false, 'blockquote');
                    break;
                case 'code':
                    const selection = window.getSelection();
                    if (selection.rangeCount > 0) {
                        const range = selection.getRangeAt(0);
                        const code = document.createElement('code');
                        try {
                            range.surroundContents(code);
                        } catch(e) {
                            document.execCommand('insertHTML', false, '<code>' + selection.toString() + '</code>');
                        }
                    }
                    break;
                case 'link':
                    const url = prompt('Enter URL:');
                    if (url) {
                        document.execCommand('createLink', false, url);
                    }
                    break;
                case 'bullet':
                    document.execCommand('insertUnorderedList', false, null);
                    break;
                case 'number':
                    document.execCommand('insertOrderedList', false, null);
                    break;
                case 'image':
                    const imageUrl = prompt('Enter image URL:');
                    if (imageUrl) {
                        document.execCommand('insertImage', false, imageUrl);
                    }
                    break;
                case 'chevrons':
                    document.execCommand('insertText', false, '» ');
                    break;
            }
            
            // Keep focus on editor
            editor.focus();
        });
    });
    
    // Handle form submission
    document.getElementById('page-form').addEventListener('submit', function(e) {
        const editor = document.getElementById('editor');
        const textarea = document.getElementById('content-textarea');
        const kind = document.getElementById('kind-hidden').value;
        
        // Only set content for text-based page types
        if (kind !== 'picture' && kind !== 'divider') {
            textarea.value = editor.innerHTML;
        } else if (kind === 'divider') {
            textarea.value = ''; // Dividers don't have content
        }
    });
    
    // Keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        // Check if we're in the editor
        if (!editor.contains(document.activeElement) && document.activeElement !== editor) {
            return;
        }
        
        // Enter key - insert paragraph break
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            document.execCommand('insertParagraph', false);
            return;
        }
        
        // Shift+Enter - insert line break
        if (e.key === 'Enter' && e.shiftKey) {
            e.preventDefault();
            document.execCommand('insertLineBreak', false);
            return;
        }
        
        // Cmd/Ctrl + B for bold
        if ((e.metaKey || e.ctrlKey) && e.key === 'b') {
            e.preventDefault();
            document.execCommand('bold', false, null);
        }
        
        // Cmd/Ctrl + I for italic
        if ((e.metaKey || e.ctrlKey) && e.key === 'i') {
            e.preventDefault();
            document.execCommand('italic', false, null);
        }
    });
});
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>