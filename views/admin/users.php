<?php
// Admin users management
?>
<h1>Users Management</h1>

<div id="users-list" style="background:#fff;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1);overflow:hidden">
    <p style="padding:20px">Loading users...</p>
</div>

<script>
fetch('/GuillaumeHousing/api/admin/users')
    .then(r => r.json())
    .then(users => {
        let html = '<table style="width:100%;margin:0;border:none">';
        html += '<thead style="background:#f5f5f5"><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>';
        html += '<tbody>';
        users.forEach(u => {
            const roleColor = u.role === 'admin' ? '#27ae60' : '#95a5a6';
            html += `<tr>
                <td>${u.id}</td>
                <td><strong>${u.name}</strong></td>
                <td>${u.email}</td>
                <td>${u.phone || 'N/A'}</td>
                <td><span style="background:${roleColor};color:#fff;padding:4px 8px;border-radius:3px;font-size:12px">${u.role.toUpperCase()}</span></td>
                <td>${new Date(u.created_at).toLocaleDateString()}</td>
                <td style="font-size:12px">
                    <a href="#" onclick="toggleRole(${u.id}, '${u.role}'); return false" style="color:#3498db;text-decoration:none;margin-right:8px">${u.role === 'admin' ? 'Make User' : 'Make Admin'}</a> |
                    <a href="#" onclick="deleteUser(${u.id}); return false" style="color:#e74c3c;text-decoration:none;margin-left:8px">Delete</a>
                </td>
            </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('users-list').innerHTML = html;
    })
    .catch(e => {
        document.getElementById('users-list').innerHTML = '<p style="padding:20px;color:#e74c3c">Error loading users</p>';
    });

function toggleRole(id, currentRole) {
    const newRole = currentRole === 'admin' ? 'user' : 'admin';
    if (confirm(`Change role to ${newRole.toUpperCase()}?`)) {
        fetch('/GuillaumeHousing/api/admin/user/role/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ role: newRole })
        })
        .then(() => location.reload())
        .catch(e => alert('Error updating role'));
    }
}

function deleteUser(id) {
    if (confirm('Delete this user? This action cannot be undone.')) {
        fetch('/GuillaumeHousing/api/admin/user/delete/' + id, { method: 'POST' })
            .then(() => location.reload())
            .catch(e => alert('Error deleting user'));
    }
}
</script>
