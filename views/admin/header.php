<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Guillaume Housing</title>
    <link rel="stylesheet" href="/GuillaumeHousing/style.css">
</head>
<body>
    <div class="admin-topbar">
        <strong>Guillaume Housing — Admin</strong>
        <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?> | <a href="/GuillaumeHousing/admin/logout">Logout</a></span>
    </div>
    <div class="admin-wrapper">
