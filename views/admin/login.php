<?php
// Minimal admin login view
?>
<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin Login - Guillaume Housing</title>
    <link rel="stylesheet" href="/GuillaumeHousing/style.css">
    <style>
        .admin-login { max-width:360px; margin:80px auto; padding:20px; border:1px solid #ddd; background:#fff; }
        .admin-login h2 { margin-top:0 }
        .admin-login input { width:100%; padding:8px; margin:6px 0 }
        .admin-login button { padding:8px 12px }
        .error { color:crimson }
    </style>
</head>
<body>
    <div class="admin-login">
        <h2>Admin Login</h2>
        <?php if (!empty($error)): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="post" action="/GuillaumeHousing/admin/login">
            <label>Email</label>
            <input type="email" name="email" required>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit">Sign in</button>
        </form>
        <p style="margin-top:10px"><a href="/GuillaumeHousing/">Back to site</a></p>
    </div>
</body>
</html>
