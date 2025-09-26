<?php 
$showBackButton = true;
include __DIR__ . '/../partials/header.php'; 
?>

<div class="container">
    <!-- Breadcrumb -->
    <div class="breadcrumb-nav">
        <a href="/books/<?= htmlspecialchars($book['slug']) ?>/edit" class="breadcrumb-link">
            <?= htmlspecialchars($book['title']) ?>
        </a>
        <span class="breadcrumb-separator">/</span>
        <span class="breadcrumb-current"><?= htmlspecialchars($page['title']) ?></span>
    </div>
    <div class="editor-container">
        <!-- Editor Toolbar -->
        <div class="editor-toolbar">
            <div class="toolbar-group">
                <div class="view-mode-toggle">
                    <input type="checkbox" id="view-toggle" class="toggle-input" checked>
                    <label for="view-toggle" class="toggle-label">
                        <span class="toggle-icon view-icon">
                            <i class="fa-regular fa-eye"></i>
                        </span>
                        <span class="toggle-icon edit-icon">
                            <i class="fa-regular fa-pen-to-square"></i>
                        </span>
                        <span class="toggle-slider"></span>
                    </label>
                </div>
            </div>
            
            <div class="toolbar-divider"></div>
            
            <div class="toolbar-group">
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
            
            <div class="toolbar-divider"></div>
            
            <div class="toolbar-group">
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
            
            <div class="toolbar-divider"></div>
            
            <div class="toolbar-group">
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
            
            <div class="toolbar-divider"></div>
            
            <div class="toolbar-group">
                <button class="toolbar-btn" data-command="history" title="History">
                    <i class="fa-solid fa-clock-rotate-left"></i>
                </button>
                <button class="toolbar-btn" data-command="check" title="Save">
                    <i class="fa-solid fa-check"></i>
                </button>
                <span id="save-indicator" style="display: none; color: #10b981; font-size: 14px; margin-left: 8px; align-self: center;">Saved</span>
            </div>
        </div>
        
        <!-- Editor Content Area -->
        <div class="editor-content">
            <form method="POST" action="/pages/<?= $page['id'] ?>/update" id="page-form">
                <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                <input type="hidden" name="kind" value="<?= htmlspecialchars($page['kind'] ?? 'chapter') ?>">
                
                <!-- Title Input -->
                <div class="editor-title">
                    <input type="text" 
                           name="title" 
                           class="title-input" 
                           placeholder="Page title..." 
                           value="<?= htmlspecialchars($page['title']) ?>"
                           required>
                </div>
                
                <!-- Content Editor -->
                <div class="editor-body">
                    <div class="editor-wrapper">
                        <div id="editor" class="content-editor" contenteditable="true">
                            <?= $page['content'] ?? '' ?>
                        </div>
                        <textarea name="content" id="content-textarea" style="display: none;"><?= htmlspecialchars($page['content'] ?? '') ?></textarea>
                    </div>
                </div>
                
                <!-- Editor Footer -->
                <div class="editor-footer">
                    <div class="footer-left">
                        <div class="word-count">
                            <span id="word-count">0</span> words
                        </div>
                        <div class="last-saved" id="last-saved" style="font-size: 12px; color: #999999; margin-top: 4px;">
                            <?php if (isset($page['updated_at'])): ?>
                                Last saved: <span id="last-saved-time"><?= date('M j, g:i A', strtotime($page['updated_at'])) ?></span>
                            <?php else: ?>
                                <span id="last-saved-time">Not saved yet</span>
                            <?php endif; ?>
                        </div>
                    </div>
                    <div class="editor-actions">
                        <button type="button" class="btn-secondary" onclick="window.location.href='/books/<?= htmlspecialchars($book['slug']) ?>/edit'">Cancel</button>
                        <button type="submit" class="btn-primary">Save Page</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
/* Breadcrumb Navigation */
.breadcrumb-nav {
    margin-bottom: 24px;
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
    overflow: hidden;
    margin-bottom: 40px;
}

/* Editor Toolbar */
.editor-toolbar {
    background: transparent;
    padding: 8px 16px;
    display: flex;
    align-items: center;
    gap: 8px;
    flex-wrap: wrap;
    border-bottom: 1px solid #F0F0F0;
}

.toolbar-group {
    display: flex;
    align-items: center;
    gap: 4px;
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Hide formatting tools in view mode */
.editor-toolbar.view-mode .toolbar-group:not(:first-child) {
    opacity: 0;
    transform: translateY(-10px);
    pointer-events: none;
    display: none;
}

.editor-toolbar.view-mode .toolbar-divider {
    display: none;
}

/* View Mode Toggle */
.view-mode-toggle {
    display: flex;
    align-items: center;
}

.toggle-input {
    display: none;
}

.toggle-label {
    position: relative;
    display: inline-block;
    width: 64px;
    height: 32px;
    background: #E5E5E5;
    border-radius: 16px;
    cursor: pointer;
    transition: background 0.3s ease;
}

.toggle-slider {
    position: absolute;
    top: 3px;
    left: 3px;
    width: 26px;
    height: 26px;
    background: white;
    border-radius: 50%;
    transition: transform 0.3s ease;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.toggle-input:checked + .toggle-label .toggle-slider {
    transform: translateX(32px);
}

.toggle-icon {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 14px;
    color: #666666;
    transition: opacity 0.3s ease;
}

.view-icon {
    left: 8px;
    opacity: 1;
}

.edit-icon {
    right: 8px;
    opacity: 0.3;
}

.toggle-input:checked + .toggle-label .view-icon {
    opacity: 0.3;
}

.toggle-input:checked + .toggle-label .edit-icon {
    opacity: 1;
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

/* Editor Content */
.editor-content {
    padding: 0;
}

/* Title Input */
.editor-title {
    padding: 40px 60px 20px;
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
    min-height: 500px;
}

.editor-wrapper {
    max-width: 700px;
    margin: 0 auto;
}

.content-editor {
    font-family: 'Georgia', serif;
    font-size: 19px;
    line-height: 1.8;
    color: #111111;
    min-height: 400px;
    outline: none;
}

.content-editor:focus {
    outline: none;
}

/* Editor Typography - Clean reading experience */
.content-editor h1 {
    font-size: 28px;
    font-weight: 700;
    margin: 32px 0 16px;
    color: #111111;
    font-family: 'Inter', sans-serif;
}

.content-editor h2 {
    font-size: 24px;
    font-weight: 600;
    margin: 28px 0 14px;
    color: #111111;
    font-family: 'Inter', sans-serif;
}

.content-editor h3 {
    font-size: 20px;
    font-weight: 600;
    margin: 24px 0 12px;
    color: #111111;
    font-family: 'Inter', sans-serif;
}

.content-editor p {
    margin: 0 0 20px;
    line-height: 1.8;
}

.content-editor strong,
.content-editor b {
    font-weight: 600;
}

.content-editor em,
.content-editor i {
    font-style: italic;
}

.content-editor ul,
.content-editor ol {
    margin: 0 0 16px;
    padding-left: 24px;
}

.content-editor li {
    margin-bottom: 8px;
}

.content-editor blockquote {
    border-left: 3px solid #E5E5E5;
    padding-left: 20px;
    margin: 16px 0;
    color: #666666;
    font-style: italic;
}

.content-editor code {
    background: #F5F5F5;
    padding: 2px 6px;
    border-radius: 3px;
    font-family: 'Monaco', 'Courier New', monospace;
    font-size: 0.9em;
    color: #333333;
}

.content-editor pre {
    background: #F5F5F5;
    padding: 16px;
    border-radius: 6px;
    overflow-x: auto;
    margin: 16px 0;
}

.content-editor pre code {
    background: transparent;
    padding: 0;
}

.content-editor a {
    color: #111111;
    text-decoration: underline;
}

.content-editor a:hover {
    color: #666666;
}

.content-editor img {
    max-width: 100%;
    height: auto;
    display: block;
    margin: 16px auto;
    border-radius: 6px;
}

/* Editor Footer */
.editor-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 20px 60px;
    background: white;
    border-top: 1px solid #F5F5F5;
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
    .editor-toolbar {
        padding: 8px;
        gap: 4px;
    }
    
    .toolbar-btn {
        width: 32px;
        height: 32px;
        font-size: 14px;
    }
    
    .editor-title {
        padding: 20px;
    }
    
    .title-input {
        font-size: 24px;
    }
    
    .editor-body {
        padding: 20px;
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

// Initialize editor with proper formatting
document.addEventListener('DOMContentLoaded', function() {
    const editor = document.getElementById('editor');
    
    // If content has HTML tags but they're escaped, unescape them
    const content = editor.innerHTML;
    if (content.includes('&lt;') || content.includes('&gt;')) {
        // Content is escaped HTML, decode it
        const textarea = document.createElement('textarea');
        textarea.innerHTML = content;
        editor.innerHTML = textarea.value;
    }
    
    // Ensure proper paragraph handling
    if (!editor.innerHTML.trim() || editor.innerHTML === '<br>') {
        editor.innerHTML = '<p><br></p>';
    }
    
    // Initialize word count
    updateWordCount();
});

// Update word count on input
document.getElementById('editor').addEventListener('input', function() {
    updateWordCount();
    
    // Ensure we always have at least one paragraph
    const editor = this;
    if (!editor.innerHTML.trim()) {
        editor.innerHTML = '<p><br></p>';
    }
});

// Handle form submission via AJAX
document.getElementById('page-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const editor = document.getElementById('editor');
    const textarea = document.getElementById('content-textarea');
    textarea.value = editor.innerHTML;
    
    // Get form data
    const formData = new FormData(this);
    
    // Show saving indicator
    const saveBtn = document.querySelector('.btn-primary');
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    // Also update toolbar save button if exists
    const toolbarSave = document.querySelector('[data-command="check"]');
    if (toolbarSave) {
        toolbarSave.style.color = '#999999';
    }
    
    // Submit via fetch
    fetch(this.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(data => {
        // Show success feedback
        saveBtn.textContent = 'Saved!';
        saveBtn.style.background = '#10b981';
        
        if (toolbarSave) {
            toolbarSave.style.color = '#10b981';
        }
        
        // Show "Saved" indicator next to check mark
        const saveIndicator = document.getElementById('save-indicator');
        if (saveIndicator) {
            saveIndicator.style.display = 'inline';
        }
        
        // Update last saved time with date
        const now = new Date();
        const dateString = now.toLocaleDateString('en-US', { 
            month: 'short',
            day: 'numeric'
        });
        const timeString = now.toLocaleTimeString('en-US', { 
            hour: 'numeric', 
            minute: '2-digit',
            hour12: true 
        });
        const fullTimeString = dateString + ', ' + timeString;
        
        const lastSavedElement = document.getElementById('last-saved-time');
        if (lastSavedElement) {
            lastSavedElement.textContent = fullTimeString;
            
            // Update the parent text if needed
            const lastSavedDiv = document.getElementById('last-saved');
            if (lastSavedDiv && !lastSavedDiv.innerHTML.includes('Last saved:')) {
                lastSavedDiv.innerHTML = 'Last saved: <span id="last-saved-time">' + fullTimeString + '</span>';
            }
        }
        
        // Clear draft since we saved
        localStorage.removeItem('draft-page-' + <?= $page['id'] ?>);
        
        // Reset button after 2 seconds
        setTimeout(() => {
            saveBtn.textContent = originalText;
            saveBtn.disabled = false;
            saveBtn.style.background = '';
            
            if (toolbarSave) {
                toolbarSave.style.color = '';
            }
            
            // Hide save indicator after 3 seconds
            if (saveIndicator) {
                setTimeout(() => {
                    saveIndicator.style.display = 'none';
                }, 1000);
            }
        }, 2000);
    })
    .catch(error => {
        console.error('Error saving:', error);
        saveBtn.textContent = 'Error saving';
        saveBtn.style.background = '#ef4444';
        
        setTimeout(() => {
            saveBtn.textContent = originalText;
            saveBtn.disabled = false;
            saveBtn.style.background = '';
        }, 3000);
    });
});

// View mode toggle
document.getElementById('view-toggle')?.addEventListener('change', function() {
    const isEditMode = this.checked;
    const editor = document.getElementById('editor');
    const titleInput = document.querySelector('.title-input');
    const toolbar = document.querySelector('.editor-toolbar');
    
    if (isEditMode) {
        // Edit mode - show all toolbar buttons
        editor.setAttribute('contenteditable', 'true');
        titleInput.removeAttribute('readonly');
        editor.style.cursor = 'text';
        toolbar.classList.remove('view-mode');
    } else {
        // View mode - hide formatting buttons
        editor.setAttribute('contenteditable', 'false');
        titleInput.setAttribute('readonly', 'readonly');
        editor.style.cursor = 'default';
        toolbar.classList.add('view-mode');
    }
});

// Toolbar button handlers
document.querySelectorAll('.toolbar-btn').forEach(btn => {
    btn.addEventListener('click', function(e) {
        e.preventDefault();
        const command = this.dataset.command;
        const editor = document.getElementById('editor');
        
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
                // Wrap selection in code tags
                const selection = window.getSelection();
                if (selection.rangeCount > 0) {
                    const range = selection.getRangeAt(0);
                    const code = document.createElement('code');
                    try {
                        range.surroundContents(code);
                    } catch(e) {
                        // If selection spans multiple elements, insert at cursor
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
            case 'check':
                // Trigger the form submit which will be caught by our AJAX handler
                document.getElementById('page-form').requestSubmit();
                break;
            case 'history':
                alert('Version history coming soon');
                break;
            case 'chevrons':
                // Custom formatting
                document.execCommand('insertText', false, '» ');
                break;
        }
        
        // Keep focus on editor
        document.getElementById('editor').focus();
    });
});

// Auto-save draft every 30 seconds
setInterval(function() {
    const editor = document.getElementById('editor');
    const title = document.querySelector('.title-input').value;
    if (editor && title) {
        localStorage.setItem('draft-page-' + <?= $page['id'] ?>, JSON.stringify({
            title: title,
            content: editor.innerHTML,
            timestamp: Date.now()
        }));
    }
}, 30000);

// Load draft on page load if exists
window.addEventListener('load', function() {
    const draft = localStorage.getItem('draft-page-' + <?= $page['id'] ?>);
    if (draft) {
        const data = JSON.parse(draft);
        // Only load if draft is newer than saved content
        if (confirm('A draft was found. Would you like to restore it?')) {
            document.querySelector('.title-input').value = data.title;
            document.getElementById('editor').innerHTML = data.content;
            updateWordCount();
        }
    }
});

// Handle paste events to convert markdown to HTML
document.getElementById('editor').addEventListener('paste', function(e) {
    e.preventDefault();
    
    // Get pasted text
    const text = (e.clipboardData || window.clipboardData).getData('text');
    
    // Simple markdown to HTML conversion
    let html = text;
    
    // Convert markdown to HTML
    // Headers
    html = html.replace(/^### (.*?)$/gm, '<h3>$1</h3>');
    html = html.replace(/^## (.*?)$/gm, '<h2>$1</h2>');
    html = html.replace(/^# (.*?)$/gm, '<h1>$1</h1>');
    
    // Bold and italic
    html = html.replace(/\*\*\*(.*?)\*\*\*/g, '<strong><em>$1</em></strong>'); // Bold + Italic
    html = html.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>'); // Bold
    html = html.replace(/\*(.*?)\*/g, '<em>$1</em>'); // Italic
    html = html.replace(/___(.*?)___/g, '<strong><em>$1</em></strong>'); // Bold + Italic
    html = html.replace(/__(.*?)__/g, '<strong>$1</strong>'); // Bold
    html = html.replace(/_(.*?)_/g, '<em>$1</em>'); // Italic
    
    // Links
    html = html.replace(/\[([^\]]+)\]\(([^)]+)\)/g, '<a href="$2">$1</a>');
    
    // Code blocks
    html = html.replace(/```([^`]+)```/g, '<pre><code>$1</code></pre>');
    html = html.replace(/`([^`]+)`/g, '<code>$1</code>');
    
    // Blockquotes
    html = html.replace(/^> (.*?)$/gm, '<blockquote>$1</blockquote>');
    
    // Lists
    html = html.replace(/^- (.*?)$/gm, '<li>$1</li>');
    html = html.replace(/^(\d+)\. (.*?)$/gm, '<li>$2</li>');
    
    // Wrap consecutive list items in ul/ol tags
    html = html.replace(/(<li>.*?<\/li>\n?)+/g, function(match) {
        return '<ul>' + match + '</ul>';
    });
    
    // Paragraphs - wrap lines that aren't already wrapped
    const lines = html.split('\n');
    const processedLines = lines.map(line => {
        line = line.trim();
        if (line && !line.startsWith('<') && !line.endsWith('>')) {
            return '<p>' + line + '</p>';
        }
        return line;
    });
    html = processedLines.join('\n');
    
    // Clean up empty paragraphs
    html = html.replace(/<p>\s*<\/p>/g, '');
    
    // Insert the converted HTML
    document.execCommand('insertHTML', false, html);
});

// Keyboard shortcuts and editor behavior
document.addEventListener('keydown', function(e) {
    const editor = document.getElementById('editor');
    
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
    
    // Cmd/Ctrl + S to save
    if ((e.metaKey || e.ctrlKey) && e.key === 's') {
        e.preventDefault();
        document.getElementById('page-form').requestSubmit();
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
</script>

<?php include __DIR__ . '/../partials/footer.php'; ?>