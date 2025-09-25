<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Access Denied - Strybk</title>
    <style>
        :root {
            --purple: #6C4AB6;
            --indigo: #2E1A47;
            --gray-600: #4b5563;
        }
        
        body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: linear-gradient(135deg, var(--indigo) 0%, var(--purple) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
            color: white;
        }
        
        .container {
            text-align: center;
            padding: 40px;
        }
        
        .error-code {
            font-size: 120px;
            font-weight: bold;
            margin-bottom: 16px;
            opacity: 0.9;
        }
        
        h1 {
            font-size: 32px;
            margin-bottom: 16px;
        }
        
        p {
            font-size: 18px;
            opacity: 0.9;
            margin-bottom: 32px;
        }
        
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: rgba(255,255,255,0.2);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.2s;
        }
        
        .btn:hover {
            background: rgba(255,255,255,0.3);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-code">403</div>
        <h1>Access Denied</h1>
        <p>This book is private. You don't have permission to view it.</p>
        <a href="/books" class="btn">Browse Public Books</a>
    </div>
</body>
</html>