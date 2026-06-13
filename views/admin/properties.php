<?php
// Admin properties management
?>
<h1>Properties Management</h1>
<div style="margin-bottom:20px">
    <a href="/GuillaumeHousing/admin/properties/create" style="padding:10px 16px;background:#27ae60;color:#fff;text-decoration:none;border-radius:4px;display:inline-block">+ Add New Property</a>
</div>

<div id="properties-list" style="background:#fff;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1);overflow:hidden">
    <p style="padding:20px">Loading properties...</p>
</div>

<script>
// Fetch and display properties
fetch('/GuillaumeHousing/api/properties')
    .then(r => r.json())
    .then(properties => {
        let html = '<table style="width:100%;margin:0;border:none">';
        html += '<thead style="background:#f5f5f5"><tr><th>ID</th><th>Title</th><th>Location</th><th>Price</th><th>Status</th><th>Featured</th><th>Actions</th></tr></thead>';
        html += '<tbody>';
        properties.forEach(p => {
            html += `<tr>
                <td>${p.id}</td>
                <td><strong>${p.title}</strong></td>
                <td>${p.location}</td>
                <td>FCFA ${ Number(p.price).toLocaleString()}</td>
                <td><span style="background:#f0f0f0;padding:4px 8px;border-radius:3px;font-size:12px">${p.status}</span></td>
                <td>${p.is_featured ? '<span style="color:#27ae60;font-weight:bold">★</span>' : '-'}</td>
                <td style="font-size:12px">
                    <a href="/GuillaumeHousing/admin/properties/edit/${p.id}" style="color:#3498db;text-decoration:none;margin-right:8px">Edit</a> |
                    <a href="#" onclick="deleteProperty(${p.id}); return false" style="color:#e74c3c;text-decoration:none;margin:0 8px">Delete</a> |
                    <a href="#" onclick="toggleFeatured(${p.id}, ${p.is_featured ? 1 : 0}); return false" style="color:#f39c12;text-decoration:none;margin-left:8px">${p.is_featured ? 'Unfeature' : 'Feature'}</a>
                </td>
            </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('properties-list').innerHTML = html;
    })
    .catch(e => {
        document.getElementById('properties-list').innerHTML = '<p style="padding:20px;color:#e74c3c">Error loading properties</p>';
    });

function deleteProperty(id) {
    if (confirm('Delete this property? This action cannot be undone.')) {
        fetch('/GuillaumeHousing/api/property/delete/' + id, { method: 'POST' })
            .then(() => location.reload())
            .catch(e => alert('Error deleting property'));
    }
}

function toggleFeatured(id, currentState) {
    const newState = currentState ? 0 : 1;
    fetch('/GuillaumeHousing/api/property/featured/' + id, {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ is_featured: newState })
    })
    .then(() => location.reload())
    .catch(e => alert('Error updating property'));
}
</script>
