<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <title>Admin - Guillaume Housing</title>
    <link rel="stylesheet" href="/GuillaumeHousing/style.css">
    <style>
        .admin-wrapper { display:flex; min-height:calc(100vh - 120px) }
        .admin-content { flex:1; padding:20px; background:#fafafa }
        .admin-sidebar { width:220px; background:#2c3e50; padding:12px; color:#fff; position:sticky; top:0 }
        .admin-sidebar a { color:#ecf0f1; display:flex; align-items:center; gap:10px; padding:8px 8px; text-decoration:none; border-radius:4px }
        .admin-sidebar a:hover { background:#34495e; color:#3498db }
        .admin-sidebar svg { width:18px; height:18px }
        .admin-topbar { background:#34495e; color:#fff; padding:12px 20px; border-bottom:1px solid #2c3e50; display:flex; justify-content:space-between; align-items:center }
        .admin-topbar strong { font-size:16px }
        .admin-topbar a { color:#ecf0f1; text-decoration:none }
        .admin-topbar a:hover { color:#3498db }
        table { border-collapse:collapse; width:100% }
        th, td { padding:12px; text-align:left; border-bottom:1px solid #ddd }
        th { background:#f5f5f5; font-weight:bold }
        tr:hover { background:#f9f9f9 }
    </style>
</head>
<body>
    <div class="admin-topbar">
        <strong>Guillaume Housing — Admin</strong>
        <span><?php echo htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?> | <a href="/GuillaumeHousing/admin/logout">Logout</a></span>
    </div>
    <div class="admin-wrapper">
