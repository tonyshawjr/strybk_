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
        <span class="breadcrumb-current"><?= htmlspecialchars($page['title']) ?></span>
    </div>
    <div class="editor-container">
        <!-- Editor Toolbar -->
        <div class="editor-toolbar">
            <div class="editor-toolbar-inner">
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
        </div>
        
        <!-- Editor Content Area -->
        <div class="editor-content">
            <form method="POST" action="/pages/<?= $page['id'] ?>/update" id="page-form">
                <input type="hidden" name="_token" value="<?= $auth->csrfToken() ?>">
                <input type="hidden" name="kind" value="<?= htmlspecialchars($page['kind'] ?? 'chapter') ?>">
                <input type="hidden" name="book_id" value="<?= htmlspecialchars($page['book_id']) ?>">
                
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
    transition: opacity 0.3s ease, transform 0.3s ease;
}

/* Hide formatting tools in view mode */
.editor-toolbar.view-mode .editor-toolbar-inner .toolbar-group:not(:first-child) {
    opacity: 0;
    transform: translateY(-10px);
    pointer-events: none;
    display: none;
}

.editor-toolbar.view-mode .editor-toolbar-inner .toolbar-divider {
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
    max-width: calc(700px + 120px); /* Content width + padding */
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
    min-height: 500px;
    max-width: calc(700px + 120px); /* Content width + padding */
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
                openVersionHistory();
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
// Version History Modal Functions
function openVersionHistory() {
    const pageId = <?= $page['id'] ?>;
    
    // Create modal if it doesn't exist
    let modal = document.getElementById('version-history-modal');
    if (!modal) {
        modal = createVersionHistoryModal();
        document.body.appendChild(modal);
    }
    
    // Show modal
    modal.style.display = 'flex';
    
    // Load version history
    loadVersionHistory(pageId);
}

function createVersionHistoryModal() {
    const modal = document.createElement('div');
    modal.id = 'version-history-modal';
    modal.className = 'version-modal';
    modal.innerHTML = `
        <div class="version-modal-content">
            <div class="version-modal-header">
                <h2>Version History</h2>
                <button class="close-modal" onclick="closeVersionHistory()">
                    <i class="fa-solid fa-times"></i>
                </button>
            </div>
            <div class="version-modal-body">
                <div class="version-loading">
                    <i class="fa-solid fa-spinner fa-spin"></i>
                    Loading versions...
                </div>
                <div class="version-list" id="version-list" style="display: none;"></div>
                <div class="version-viewer" id="version-viewer" style="display: none;"></div>
            </div>
        </div>
    `;
    return modal;
}

function closeVersionHistory() {
    const modal = document.getElementById('version-history-modal');
    if (modal) {
        modal.style.display = 'none';
    }
}

function loadVersionHistory(pageId) {
    fetch(`/pages/${pageId}/history`)
        .then(response => response.json())
        .then(data => {
            displayVersionList(data);
        })
        .catch(error => {
            console.error('Error loading version history:', error);
            document.querySelector('.version-loading').innerHTML = 
                '<p style="color: #ef4444;">Error loading version history</p>';
        });
}

function displayVersionList(data) {
    const loader = document.querySelector('.version-loading');
    const list = document.getElementById('version-list');
    
    loader.style.display = 'none';
    list.style.display = 'block';
    
    if (!data.versions || data.versions.length === 0) {
        list.innerHTML = '<p style="text-align: center; color: #999;">No version history available yet.</p>';
        return;
    }
    
    let html = '<div class="version-stats">';
    if (data.stats) {
        html += `
            <div class="stat-item">
                <span class="stat-label">Total Versions:</span>
                <span class="stat-value">${data.stats.total_versions || 0}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">First Version:</span>
                <span class="stat-value">${formatDate(data.stats.first_version_date)}</span>
            </div>
            <div class="stat-item">
                <span class="stat-label">Latest Version:</span>
                <span class="stat-value">${formatDate(data.stats.last_version_date)}</span>
            </div>
        `;
    }
    html += '</div>';
    
    html += '<div class="version-items">';
    data.versions.forEach((version, index) => {
        const isCurrent = index === 0;
        html += `
            <div class="version-item ${isCurrent ? 'current' : ''}">
                <div class="version-header">
                    <div class="version-info">
                        <span class="version-number">Version ${version.version_number}</span>
                        ${isCurrent ? '<span class="version-badge">Current</span>' : ''}
                    </div>
                    <div class="version-meta">
                        <span class="version-author">${version.author_name || 'Unknown'}</span>
                        <span class="version-date">${formatDate(version.created_at)}</span>
                    </div>
                </div>
                <div class="version-title">${escapeHtml(version.title)}</div>
                <div class="version-stats-inline">
                    <span>${version.word_count || 0} words</span>
                </div>
                <div class="version-actions">
                    <button class="btn-version-view" onclick="viewVersion(${data.page.id}, ${version.version_number})">
                        <i class="fa-solid fa-eye"></i> View
                    </button>
                    ${!isCurrent ? `
                        <button class="btn-version-compare" onclick="compareWithCurrent(${data.page.id}, ${version.version_number})">
                            <i class="fa-solid fa-code-compare"></i> Compare
                        </button>
                        <button class="btn-version-restore" onclick="confirmRestore(${data.page.id}, ${version.version_number})">
                            <i class="fa-solid fa-undo"></i> Restore
                        </button>
                    ` : ''}
                </div>
            </div>
        `;
    });
    html += '</div>';
    
    list.innerHTML = html;
}

function viewVersion(pageId, versionNumber) {
    const viewer = document.getElementById('version-viewer');
    const list = document.getElementById('version-list');
    
    fetch(`/pages/${pageId}/version/${versionNumber}`)
        .then(response => response.json())
        .then(version => {
            list.style.display = 'none';
            viewer.style.display = 'block';
            viewer.innerHTML = `
                <div class="version-view-header">
                    <button class="btn-back" onclick="backToVersionList()">
                        <i class="fa-solid fa-arrow-left"></i> Back to History
                    </button>
                    <div class="version-view-title">
                        <h3>Version ${version.version_number}</h3>
                        <span class="version-view-meta">
                            by ${version.author_name || 'Unknown'} on ${formatDate(version.created_at)}
                        </span>
                    </div>
                </div>
                <div class="version-view-content">
                    <h4>${escapeHtml(version.title)}</h4>
                    <div class="version-content-body">${version.content || '<p style="color: #999;">No content</p>'}</div>
                </div>
            `;
        })
        .catch(error => {
            console.error('Error loading version:', error);
            viewer.innerHTML = '<p style="color: #ef4444; text-align: center;">Error loading version</p>';
        });
}

function backToVersionList() {
    document.getElementById('version-viewer').style.display = 'none';
    document.getElementById('version-list').style.display = 'block';
}

function compareWithCurrent(pageId, versionNumber) {
    // Load both versions and display diff
    const modal = document.getElementById('version-history-modal');
    const viewer = document.getElementById('version-viewer');
    const list = document.getElementById('version-list');
    const loader = document.querySelector('.version-loading');
    const statsDiv = document.querySelector('.version-stats');
    const itemsDiv = document.querySelector('.version-items');
    
    // Hide all list elements
    if (loader) loader.style.display = 'none';
    if (statsDiv) statsDiv.style.display = 'none';
    if (itemsDiv) itemsDiv.style.display = 'none';
    list.style.display = 'none';
    
    // Show viewer
    viewer.style.display = 'block';
    viewer.innerHTML = '<div class="version-loading"><i class="fa-solid fa-spinner fa-spin"></i> Loading comparison...</div>';
    
    // Fetch comparison data
    fetch(`/pages/${pageId}/compare/${versionNumber}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayComparison(data);
            } else {
                viewer.innerHTML = '<p style="color: #ef4444;">Error loading comparison</p>';
            }
        })
        .catch(error => {
            console.error('Error loading comparison:', error);
            viewer.innerHTML = '<p style="color: #ef4444;">Error loading comparison</p>';
        });
}

function displayComparison(data) {
    const viewer = document.getElementById('version-viewer');
    
    // Generate diff view
    const diffHtml = generateDiffView(
        data.version1.content || '', 
        data.version2.content || ''
    );
    
    let html = `
        <div class="version-compare">
            <div class="compare-header">
                <button class="btn-back" onclick="backToVersionList()">
                    <i class="fa-solid fa-arrow-left"></i> Back to History
                </button>
                <h3>Comparing Version ${data.version1.version_number} with Current Version</h3>
            </div>
            <div class="compare-stats">
                <div class="stat-item">
                    <span class="stat-label">Version ${data.version1.version_number}:</span>
                    <span class="stat-value">${data.version1.word_count || 0} words</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Current Version:</span>
                    <span class="stat-value">${data.version2.word_count || 0} words</span>
                </div>
                <div class="stat-item">
                    <span class="stat-label">Difference:</span>
                    <span class="stat-value">${Math.abs((data.version2.word_count || 0) - (data.version1.word_count || 0))} words</span>
                </div>
            </div>
            <div class="compare-view-toggle">
                <button class="toggle-btn active" onclick="toggleCompareView('diff', this)">
                    <i class="fa-solid fa-code-compare"></i> Diff View
                </button>
                <button class="toggle-btn" onclick="toggleCompareView('side', this)">
                    <i class="fa-solid fa-columns"></i> Side by Side
                </button>
            </div>
            <div id="compare-diff-view" style="display: block;">
                ${diffHtml}
            </div>
            <div class="compare-content" id="compare-side-view" style="display: none;">
                <div class="version-side">
                    <h4>Version ${data.version1.version_number} <small>(${formatDate(data.version1.created_at)})</small></h4>
                    <div class="version-content">${data.version1.content || '<em>Empty</em>'}</div>
                </div>
                <div class="version-side">
                    <h4>Current Version</h4>
                    <div class="version-content">${data.version2.content || '<em>Empty</em>'}</div>
                </div>
            </div>
        </div>
    `;
    
    viewer.innerHTML = html;
}

function generateDiffView(oldText, newText) {
    // Strip HTML and compare plain text
    const oldClean = stripHtml(oldText || '');
    const newClean = stripHtml(newText || '');
    
    // If texts are identical
    if (oldClean === newClean) {
        return '<div class="diff-container"><p class="no-changes">No changes detected between versions.</p></div>';
    }
    
    // Split into words for word-level diff
    const oldWords = oldClean.split(/(\s+)/);
    const newWords = newClean.split(/(\s+)/);
    
    // Compute word-level diff
    const diff = computeWordDiff(oldWords, newWords);
    
    let diffHtml = '<div class="diff-container"><div class="diff-text">';
    
    diff.forEach(part => {
        if (part.type === 'unchanged') {
            diffHtml += escapeHtml(part.text);
        } else if (part.type === 'removed') {
            diffHtml += `<span class="diff-removed">${escapeHtml(part.text)}</span>`;
        } else if (part.type === 'added') {
            diffHtml += `<span class="diff-added">${escapeHtml(part.text)}</span>`;
        }
    });
    
    diffHtml += '</div></div>';
    
    return diffHtml;
}

function computeWordDiff(oldWords, newWords) {
    const diff = [];
    let i = 0, j = 0;
    
    // Filter out empty strings from split result
    oldWords = oldWords.filter(w => w !== '');
    newWords = newWords.filter(w => w !== '');
    
    while (i < oldWords.length || j < newWords.length) {
        if (i >= oldWords.length) {
            // Remaining new words are additions
            diff.push({ type: 'added', text: newWords[j] });
            j++;
        } else if (j >= newWords.length) {
            // Remaining old words are deletions
            diff.push({ type: 'removed', text: oldWords[i] });
            i++;
        } else if (oldWords[i] === newWords[j]) {
            // Unchanged
            diff.push({ type: 'unchanged', text: oldWords[i] });
            i++;
            j++;
        } else {
            // Check if this is just a simple word replacement (not a phrase change)
            // Look for the next matching word within a small window
            let oldNext = -1;
            let newNext = -1;
            
            // Find next match in a small window (3 words ahead)
            for (let k = 1; k <= 3; k++) {
                if (i + k < oldWords.length && j + k < newWords.length && oldWords[i + k] === newWords[j + k]) {
                    oldNext = i + k;
                    newNext = j + k;
                    break;
                }
            }
            
            if (oldNext !== -1 && newNext !== -1 && oldNext - i === newNext - j) {
                // Same number of words changed - likely a word replacement
                for (let m = 0; m < oldNext - i; m++) {
                    diff.push({ type: 'removed', text: oldWords[i + m] });
                    diff.push({ type: 'added', text: newWords[j + m] });
                }
                i = oldNext;
                j = newNext;
            } else {
                // Just mark this word as changed and continue
                diff.push({ type: 'removed', text: oldWords[i] });
                diff.push({ type: 'added', text: newWords[j] });
                i++;
                j++;
            }
        }
    }
    
    return diff;
}

function htmlToBlocks(html) {
    if (!html) return [];
    
    // Create a temporary element to parse HTML
    const temp = document.createElement('div');
    temp.innerHTML = html;
    
    const blocks = [];
    
    // Process each paragraph or block-level element
    const elements = temp.querySelectorAll('p, h1, h2, h3, h4, h5, h6, blockquote, ul, ol, div');
    
    if (elements.length === 0 && temp.textContent.trim()) {
        // No block elements, just text
        blocks.push({
            text: temp.textContent.trim(),
            html: html
        });
    } else {
        elements.forEach(el => {
            const text = el.textContent.trim();
            if (text) {
                blocks.push({
                    text: text,
                    html: el.outerHTML
                });
            }
        });
    }
    
    return blocks;
}

function stripHtml(html) {
    const temp = document.createElement('div');
    temp.innerHTML = html;
    return temp.textContent || temp.innerText || '';
}

function splitIntoSentences(text) {
    // Split by sentence endings but keep the delimiters
    return text.match(/[^.!?]+[.!?]+|[^.!?]+$/g) || [];
}

function computeDiff(oldArr, newArr) {
    const diff = [];
    let i = 0, j = 0;
    
    while (i < oldArr.length || j < newArr.length) {
        if (i >= oldArr.length) {
            // Remaining new items are additions
            diff.push({ type: 'added', text: newArr[j].text, html: newArr[j].html });
            j++;
        } else if (j >= newArr.length) {
            // Remaining old items are deletions
            diff.push({ type: 'removed', text: oldArr[i].text, html: oldArr[i].html });
            i++;
        } else if (oldArr[i].text === newArr[j].text) {
            // Unchanged
            diff.push({ type: 'unchanged', text: oldArr[i].text, html: oldArr[i].html });
            i++;
            j++;
        } else {
            // Look ahead to find matches
            let foundMatch = false;
            
            // Check if current old item exists somewhere ahead in new array
            for (let k = j + 1; k < Math.min(j + 5, newArr.length); k++) {
                if (oldArr[i].text === newArr[k].text) {
                    // Items before k are additions
                    for (let m = j; m < k; m++) {
                        diff.push({ type: 'added', text: newArr[m].text, html: newArr[m].html });
                    }
                    diff.push({ type: 'unchanged', text: oldArr[i].text, html: oldArr[i].html });
                    j = k + 1;
                    i++;
                    foundMatch = true;
                    break;
                }
            }
            
            if (!foundMatch) {
                // Check if current new item exists somewhere ahead in old array
                for (let k = i + 1; k < Math.min(i + 5, oldArr.length); k++) {
                    if (newArr[j].text === oldArr[k].text) {
                        // Items before k are deletions
                        for (let m = i; m < k; m++) {
                            diff.push({ type: 'removed', text: oldArr[m].text, html: oldArr[m].html });
                        }
                        diff.push({ type: 'unchanged', text: newArr[j].text, html: newArr[j].html });
                        i = k + 1;
                        j++;
                        foundMatch = true;
                        break;
                    }
                }
            }
            
            if (!foundMatch) {
                // No match found, treat as remove and add
                diff.push({ type: 'removed', text: oldArr[i].text, html: oldArr[i].html });
                diff.push({ type: 'added', text: newArr[j].text, html: newArr[j].html });
                i++;
                j++;
            }
        }
    }
    
    return diff;
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function toggleCompareView(view, button) {
    // Update active button
    document.querySelectorAll('.toggle-btn').forEach(btn => {
        btn.classList.remove('active');
    });
    button.classList.add('active');
    
    // Show/hide views
    const diffView = document.getElementById('compare-diff-view');
    const sideView = document.getElementById('compare-side-view');
    
    if (view === 'diff') {
        diffView.style.display = 'block';
        sideView.style.display = 'none';
    } else {
        diffView.style.display = 'none';
        sideView.style.display = 'grid';
    }
}

function backToVersionList() {
    const viewer = document.getElementById('version-viewer');
    const list = document.getElementById('version-list');
    const statsDiv = document.querySelector('.version-stats');
    const itemsDiv = document.querySelector('.version-items');
    
    // Hide viewer
    viewer.style.display = 'none';
    
    // Show list elements
    list.style.display = 'block';
    if (statsDiv) statsDiv.style.display = 'flex';
    if (itemsDiv) itemsDiv.style.display = 'block';
}

function confirmRestore(pageId, versionNumber) {
    if (confirm(`Are you sure you want to restore Version ${versionNumber}? This will create a new version with the content from Version ${versionNumber}.`)) {
        restoreVersion(pageId, versionNumber);
    }
}

function restoreVersion(pageId, versionNumber) {
    const csrfToken = document.querySelector('input[name="_token"]').value;
    
    // Show loading state on restore button
    const restoreButtons = document.querySelectorAll('.btn-version-restore');
    restoreButtons.forEach(btn => {
        if (btn.textContent.includes('Restore')) {
            btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Restoring...';
            btn.disabled = true;
        }
    });
    
    fetch(`/pages/${pageId}/restore/${versionNumber}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: `_token=${encodeURIComponent(csrfToken)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Clear the draft from localStorage to prevent it from overriding the restored version
            const bookIdInput = document.querySelector('input[name="book_id"]');
            if (bookIdInput) {
                const bookId = bookIdInput.value;
                const draftKey = `strybk_draft_${bookId}_${pageId}`;
                localStorage.removeItem(draftKey);
            }
            
            // Clear any autosave drafts
            if (window.autosaveInterval) {
                clearInterval(window.autosaveInterval);
            }
            
            // Show success message briefly then reload
            const modal = document.getElementById('version-history-modal');
            if (modal) {
                const modalBody = modal.querySelector('.version-modal-body');
                modalBody.innerHTML = `
                    <div style="text-align: center; padding: 60px 20px;">
                        <i class="fa-solid fa-check-circle" style="font-size: 48px; color: #22c55e; margin-bottom: 20px;"></i>
                        <h3 style="color: #111; margin-bottom: 10px;">Version Restored Successfully!</h3>
                        <p style="color: #666;">Refreshing page to show restored content...</p>
                        <div style="margin-top: 20px;">
                            <i class="fa-solid fa-spinner fa-spin" style="font-size: 24px; color: #999;"></i>
                        </div>
                    </div>
                `;
            }
            
            // Reload after a brief delay to show the success message
            setTimeout(() => {
                // Force reload with cache bypass
                location.href = location.href + '?restored=' + Date.now();
            }, 1500);
            
        } else {
            // Re-enable buttons on error
            restoreButtons.forEach(btn => {
                btn.innerHTML = '<i class="fa-solid fa-clock-rotate-left"></i> Restore';
                btn.disabled = false;
            });
            alert('Failed to restore version: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(error => {
        console.error('Error restoring version:', error);
        // Re-enable buttons on error
        const restoreButtons = document.querySelectorAll('.btn-version-restore');
        restoreButtons.forEach(btn => {
            btn.innerHTML = '<i class="fa-solid fa-clock-rotate-left"></i> Restore';
            btn.disabled = false;
        });
        alert('Error restoring version. Please try again.');
    });
}

function formatDate(dateString) {
    if (!dateString) return 'Unknown';
    const date = new Date(dateString);
    const now = new Date();
    const diffMs = now - date;
    const diffMins = Math.floor(diffMs / 60000);
    const diffHours = Math.floor(diffMs / 3600000);
    const diffDays = Math.floor(diffMs / 86400000);
    
    if (diffMins < 1) return 'Just now';
    if (diffMins < 60) return `${diffMins} minute${diffMins !== 1 ? 's' : ''} ago`;
    if (diffHours < 24) return `${diffHours} hour${diffHours !== 1 ? 's' : ''} ago`;
    if (diffDays < 7) return `${diffDays} day${diffDays !== 1 ? 's' : ''} ago`;
    
    return date.toLocaleDateString('en-US', { 
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}
</script>

<style>
/* Version History Modal Styles */
.version-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
    z-index: 10000;
    align-items: center;
    justify-content: center;
}

.version-modal-content {
    background: white;
    border-radius: 12px;
    width: 90%;
    max-width: 900px;
    max-height: 80vh;
    display: flex;
    flex-direction: column;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
}

.version-modal-header {
    padding: 24px;
    border-bottom: 1px solid #E5E5E5;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.version-modal-header h2 {
    margin: 0;
    font-size: 24px;
    font-weight: 700;
    color: #111111;
}

.close-modal {
    background: none;
    border: none;
    font-size: 24px;
    color: #999999;
    cursor: pointer;
    padding: 0;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 6px;
    transition: all 0.2s;
}

.close-modal:hover {
    background: #F5F5F5;
    color: #111111;
}

.version-modal-body {
    flex: 1;
    overflow-y: auto;
    padding: 24px;
}

.version-loading {
    text-align: center;
    padding: 48px;
    color: #999999;
}

.version-loading i {
    font-size: 32px;
    margin-bottom: 16px;
    display: block;
}

.version-stats {
    display: flex;
    gap: 24px;
    padding: 16px;
    background: #F5F5F5;
    border-radius: 8px;
    margin-bottom: 24px;
}

.stat-item {
    display: flex;
    flex-direction: column;
    gap: 4px;
}

.stat-label {
    font-size: 12px;
    color: #999999;
    text-transform: uppercase;
    letter-spacing: 0.05em;
}

.stat-value {
    font-size: 14px;
    color: #111111;
    font-weight: 600;
}

.version-items {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.version-item {
    padding: 16px;
    border: 1px solid #E5E5E5;
    border-radius: 8px;
    transition: all 0.2s;
}

.version-item:hover {
    border-color: #111111;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
}

.version-item.current {
    background: #F0FDF4;
    border-color: #10b981;
}

.version-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 8px;
}

.version-info {
    display: flex;
    align-items: center;
    gap: 8px;
}

.version-number {
    font-weight: 600;
    color: #111111;
}

.version-badge {
    background: #10b981;
    color: white;
    padding: 2px 8px;
    border-radius: 12px;
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
}

.version-meta {
    display: flex;
    gap: 12px;
    font-size: 13px;
    color: #999999;
}

.version-title {
    font-size: 16px;
    color: #333333;
    margin-bottom: 8px;
}

.version-stats-inline {
    font-size: 13px;
    color: #666666;
    margin-bottom: 12px;
}

.version-actions {
    display: flex;
    gap: 8px;
}

.btn-version-view,
.btn-version-compare,
.btn-version-restore {
    padding: 6px 12px;
    border: 1px solid #E5E5E5;
    background: white;
    border-radius: 6px;
    font-size: 13px;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
    gap: 6px;
}

.btn-version-view:hover {
    background: #F5F5F5;
    border-color: #111111;
}

.btn-version-compare:hover {
    background: #EFF6FF;
    border-color: #3B82F6;
    color: #3B82F6;
}

.btn-version-restore:hover {
    background: #FEF2F2;
    border-color: #EF4444;
    color: #EF4444;
}

/* Version Viewer Styles */
.version-view-header {
    display: flex;
    align-items: center;
    gap: 24px;
    margin-bottom: 24px;
    padding-bottom: 16px;
    border-bottom: 1px solid #E5E5E5;
}

.btn-back {
    padding: 8px 16px;
    background: #F5F5F5;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #E5E5E5;
}

.version-view-title h3 {
    margin: 0;
    font-size: 20px;
    color: #111111;
}

.version-view-meta {
    font-size: 14px;
    color: #999999;
}

.version-view-content {
    padding: 24px;
    background: #FAFAFA;
    border-radius: 8px;
}

.version-view-content h4 {
    margin: 0 0 16px 0;
    font-size: 24px;
    color: #111111;
}

.version-content-body {
    font-family: 'Georgia', serif;
    font-size: 17px;
    line-height: 1.8;
    color: #333333;
    max-height: 400px;
    overflow-y: auto;
    padding-right: 16px;
}

/* Version Comparison Styles */
.version-compare {
    padding: 20px;
}

.compare-header {
    display: flex;
    align-items: center;
    gap: 20px;
    margin-bottom: 24px;
}

.compare-header h3 {
    margin: 0;
    flex: 1;
    color: #111111;
}

.btn-back {
    background: #F5F5F5;
    border: none;
    padding: 8px 16px;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    color: #111111;
    transition: all 0.2s;
}

.btn-back:hover {
    background: #E5E5E5;
}

.compare-stats {
    display: flex;
    gap: 24px;
    padding: 16px;
    background: #F9F9F9;
    border-radius: 8px;
    margin-bottom: 24px;
}

.compare-content {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

.version-side {
    border: 1px solid #E5E5E5;
    border-radius: 8px;
    overflow: hidden;
}

.version-side h4 {
    margin: 0;
    padding: 12px 16px;
    background: #F5F5F5;
    border-bottom: 1px solid #E5E5E5;
    font-size: 16px;
    color: #111111;
}

.version-side h4 small {
    color: #999999;
    font-weight: normal;
    font-size: 14px;
}

.version-content {
    padding: 16px;
    max-height: 400px;
    overflow-y: auto;
    font-size: 14px;
    line-height: 1.6;
    color: #333333;
}

/* Compare View Toggle */
.compare-view-toggle {
    display: flex;
    gap: 8px;
    padding: 0 20px;
    margin-bottom: 20px;
}

.toggle-btn {
    padding: 8px 16px;
    background: #F5F5F5;
    border: none;
    border-radius: 6px;
    cursor: pointer;
    font-size: 14px;
    color: #666666;
    display: flex;
    align-items: center;
    gap: 8px;
    transition: all 0.2s;
}

.toggle-btn:hover {
    background: #E5E5E5;
}

.toggle-btn.active {
    background: #111111;
    color: white;
}

/* Diff View Styles */
.diff-container {
    padding: 20px;
    background: white;
    border-radius: 8px;
    font-size: 15px;
    line-height: 1.8;
    max-height: 500px;
    overflow-y: auto;
}

.diff-text {
    font-family: Georgia, serif;
    font-size: 16px;
    line-height: 1.8;
    color: #333333;
    white-space: pre-wrap;
    word-wrap: break-word;
}

.diff-removed {
    background: #ffecec;
    color: #c41e3a;
    text-decoration: line-through;
    padding: 2px 4px;
    border-radius: 3px;
    margin: 0 1px;
}

.diff-added {
    background: #e6ffec;
    color: #22863a;
    padding: 2px 4px;
    border-radius: 3px;
    margin: 0 1px;
}

.no-changes {
    text-align: center;
    color: #999999;
    font-style: italic;
    padding: 40px;
}

#compare-side-view {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 24px;
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .version-modal-content {
        width: 95%;
        max-height: 90vh;
    }
    
    .version-stats {
        flex-direction: column;
        gap: 12px;
    }
    
    .version-actions {
        flex-direction: column;
    }
    
    .btn-version-view,
    .btn-version-compare,
    .btn-version-restore {
        width: 100%;
        justify-content: center;
    }
    
    #compare-side-view {
        grid-template-columns: 1fr;
    }
    
    .compare-view-toggle {
        flex-direction: column;
    }
    
    .toggle-btn {
        width: 100%;
        justify-content: center;
    }
}
</style>

<?php include __DIR__ . '/../partials/footer.php'; ?>