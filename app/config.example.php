<?php
/**
 * Strybk_ Configuration File
 * Copy this file to config.php and update with your settings
 */

return [
    // Database Configuration
    'database' => [
        'host' => 'localhost',
        'port' => 3306,
        'name' => 'your_database_name',
        'username' => 'your_database_user',
        'password' => 'your_database_password',
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'prefix' => '', // Table prefix (optional)
    ],

    // Application Settings
    'app' => [
        'name' => 'Strybk',
        'url' => 'http://localhost:8000', // Your site URL
        'timezone' => 'UTC',
        'debug' => false, // Set to true for development
        'maintenance' => false,
    ],

    // Security Settings
    'security' => [
        'session_lifetime' => 120, // Minutes
        'csrf_token_name' => '_token',
        'password_min_length' => 8,
        'max_login_attempts' => 5,
        'lockout_duration' => 15, // Minutes
    ],

    // Upload Settings
    'uploads' => [
        'max_file_size' => 5242880, // 5MB in bytes
        'allowed_image_types' => ['jpg', 'jpeg', 'png', 'gif', 'webp'],
        'cover_dimensions' => [
            'width' => 600,
            'height' => 900,
        ],
        'storage_path' => __DIR__ . '/../public/uploads',
    ],

    // Email Settings (for future use)
    'mail' => [
        'driver' => 'mail', // mail, smtp
        'from_address' => 'noreply@example.com',
        'from_name' => 'Strybk',
    ],

    // Pagination
    'pagination' => [
        'books_per_page' => 12,
        'pages_per_book' => 50,
    ],

    // Feature Flags
    'features' => [
        'registration' => false, // Allow new user registration
        'public_books' => true, // Allow public book viewing
        'markdown_export' => false, // Enable export functionality
        'version_history' => false, // Track page versions
    ],
];