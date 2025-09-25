<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 - Page Not Found</title>
    <style>
        :root {
            --purple: #6C4AB6;
            --indigo: #2E1A47;
            --lime: #A8FF60;
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
        h1 {
            font-size: 120px;
            margin: 0;
            opacity: 0.9;
        }
        h2 {
            font-size: 32px;
            margin: 20px 0;
            font-weight: normal;
        }
        p {
            font-size: 18px;
            opacity: 0.8;
            margin-bottom: 32px;
        }
        a {
            display: inline-block;
            padding: 12px 32px;
            background: var(--lime);
            color: var(--indigo);
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            transition: transform 0.2s;
        }
        a:hover {
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Page Not Found</h2>
        <p>The page you're looking for doesn't exist.</p>
        <a href="/">← Go Home</a>
    </div>
</body>
</html>