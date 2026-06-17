<?php
// Admin users management
?>
<h1>Users Management</h1>

<div id="users-list" class="admin-card">
    <p class="load-message">Loading users...</p>
</div>

<script>
fetch('/GuillaumeHousing/api/admin/users')
    .then(r => r.json())
    .then(users => {
        let html = '<table class="admin-table">';
        html += '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Joined</th><th>Actions</th></tr></thead>';
        html += '<tbody>';
        users.forEach(u => {
            const roleClass = u.role === 'admin' ? 'role-admin' : 'role-user';
            html += `<tr>
                <td>${u.id}</td>
                <td><strong>${u.name}</strong></td>
                <td>${u.email}</td>
                <td>${u.phone || 'N/A'}</td>
                <td><span class="status-badge ${roleClass}">${u.role.toUpperCase()}</span></td>
                <td>${new Date(u.created_at).toLocaleDateString()}</td>
                <td><a href="#" onclick="toggleRole(${u.id}, '${u.role}'); return false" class="admin-btn btn-info">${u.role === 'admin' ? 'Make User' : 'Make Admin'}</a> <a href="#" onclick="deleteUser(${u.id}); return false" class="admin-btn btn-danger">Delete</a></td>
            </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('users-list').innerHTML = html;
    })
    .catch(e => {
        document.getElementById('users-list').innerHTML = '<p class="error-message">Error loading users</p>';
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
