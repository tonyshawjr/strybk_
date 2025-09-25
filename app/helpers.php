<?php
/**
 * Helper Functions
 * Common utility functions used throughout the application
 */

function slug(string $text): string {
    // Convert to lowercase
    $text = mb_strtolower($text, 'UTF-8');
    
    // Replace non-alphanumeric characters with hyphens
    $text = preg_replace('/[^a-z0-9]+/i', '-', $text);
    
    // Remove multiple hyphens
    $text = preg_replace('/-+/', '-', $text);
    
    // Trim hyphens from ends
    return trim($text, '-');
}

function escape(string $text): string {
    return htmlspecialchars($text, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header("Location: $url");
    exit;
}

function view(string $name, array $data = []): void {
    extract($data);
    require __DIR__ . "/views/$name.php";
}

function partial(string $name, array $data = []): void {
    extract($data);
    require __DIR__ . "/views/partials/$name.php";
}

function asset(string $path): string {
    return "/assets/$path";
}

function upload(string $path): string {
    return "/uploads/$path";
}

function config(string $key, mixed $default = null): mixed {
    global $config;
    
    $keys = explode('.', $key);
    $value = $config;
    
    foreach ($keys as $k) {
        if (!isset($value[$k])) {
            return $default;
        }
        $value = $value[$k];
    }
    
    return $value;
}

function old(string $key, string $default = ''): string {
    return $_SESSION['old'][$key] ?? $default;
}

function csrf_field(): string {
    global $auth;
    $token = $auth->csrfToken();
    $name = config('security.csrf_token_name', '_token');
    return '<input type="hidden" name="' . $name . '" value="' . $token . '">';
}

function method_field(string $method): string {
    return '<input type="hidden" name="_method" value="' . strtoupper($method) . '">';
}

function flash(string $message, string $type = 'info'): void {
    if (!isset($_SESSION['flash'])) {
        $_SESSION['flash'] = [];
    }
    $_SESSION['flash'][] = [
        'message' => $message,
        'type' => $type
    ];
}

function get_flash(): ?array {
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function word_count(string $text): int {
    // Strip HTML and markdown
    $text = strip_tags($text);
    $text = preg_replace('/[#*_`\[\]()!]+/', '', $text);
    
    // Count words
    return str_word_count($text);
}

function reading_time(int $words): string {
    $minutes = ceil($words / 200); // Average reading speed
    
    if ($minutes == 1) {
        return '1 min read';
    }
    
    return "$minutes min read";
}

function truncate(string $text, int $length = 100, string $suffix = '...'): string {
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    
    return mb_substr($text, 0, $length) . $suffix;
}

function format_date(string $date, string $format = 'M j, Y'): string {
    return date($format, strtotime($date));
}

function format_bytes(int $bytes, int $precision = 2): string {
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];
    
    for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
        $bytes /= 1024;
    }
    
    return round($bytes, $precision) . ' ' . $units[$i];
}