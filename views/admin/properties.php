<?php
// Admin properties management
?>
<h1>Properties Management</h1>
<a href="/GuillaumeHousing/admin/properties/create" class="admin-btn btn-primary">+ Add New Property</a>

<div id="properties-list" class="admin-card">
    <p class="load-message">Loading properties...</p>
</div>

<script>
// Fetch and display properties
fetch('/GuillaumeHousing/api/properties')
    .then(r => r.json())
    .then(properties => {
        let html = '<table class="admin-table">';
        html += '<thead><tr><th>ID</th><th>Title</th><th>Location</th><th>Price</th><th>Status</th><th>Actions</th></tr></thead>';
        html += '<tbody>';
        properties.forEach(p => {
            html += `<tr>
                <td>${p.id}</td>
                <td><strong>${p.title}</strong></td>
                <td>${p.location}</td>
                <td>FCFA ${ Number(p.price).toLocaleString()}</td>
                <td><span class="status-badge status-default">${p.status}</span></td>
                <td><a href="/GuillaumeHousing/admin/properties/edit/${p.id}" class="admin-btn btn-info">Edit</a> <a href="#" onclick="deleteProperty(${p.id}); return false" class="admin-btn btn-danger">Delete</a></td>
            </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('properties-list').innerHTML = html;
    })
    .catch(e => {
        document.getElementById('properties-list').innerHTML = '<p class="error-message">Error loading properties</p>';
    });

function deleteProperty(id) {
    if (confirm('Delete this property? This action cannot be undone.')) {
        fetch('/GuillaumeHousing/api/property/delete/' + id, { method: 'POST' })
            .then(() => location.reload())
            .catch(e => alert('Error deleting property'));
    }
}

</script>
