<?php
// Admin images management
?>
<h1>Image Gallery</h1>

<div class="admin-card">
    <div class="card-header">
        <h2>Upload New Image</h2>
    </div>
    <form id="image-upload-form" class="admin-form">
        <div class="admin-form-group">
            <label>Select Property:</label>
            <select id="property-select" required>
                <option value="">-- Select a property --</option>
            </select>
        </div>
        <div class="admin-form-group">
            <label>Upload Image:</label>
            <input type="file" id="image-input" accept="image/*" required>
        </div>
        <button type="submit" class="admin-form-submit">Upload Image</button>
    </form>
    <div id="upload-message"></div>
</div>

<div class="admin-card">
    <div class="card-header">
        <h2>All Images</h2>
        <p class="card-subtext" id="image-count">Loading...</p>
    </div>
    <div id="images-list">
        <p class="load-message">Loading images...</p>
    </div>
</div>

<script>
let allImages = [];

// Load properties for dropdown
fetch('/GuillaumeHousing/api/properties')
    .then(r => r.json())
    .then(properties => {
        const select = document.getElementById('property-select');
        properties.forEach(p => {
            const option = document.createElement('option');
            option.value = p.id;
            option.textContent = p.title;
            select.appendChild(option);
        });
    })
    .catch(e => console.error('Error loading properties:', e));

// Load all images
function loadImages() {
    fetch('/GuillaumeHousing/api/admin/images')
        .then(r => r.json())
        .then(images => {
            allImages = images;
            renderImages(images);
        })
        .catch(e => {
            document.getElementById('images-list').innerHTML = '<p class="error-message">Error loading images</p>';
        });
}

function renderImages(images) {
    const countEl = document.getElementById('image-count');
    countEl.textContent = images.length + ' image' + (images.length !== 1 ? 's' : '');
    
    if (images.length === 0) {
        document.getElementById('images-list').innerHTML = '<p class="load-message">No images uploaded yet</p>';
        return;
    }

    let html = '<table class="admin-table">';
    html += '<thead><tr><th>ID</th><th>Property</th><th>View</th><th>Uploaded</th><th>Actions</th></tr></thead>';
    html += '<tbody>';
    images.forEach(img => {
        const uploadDate = new Date(img.created_at).toLocaleDateString();
        html += `<tr>
            <td>${img.id}</td>
            <td><strong>${img.property_title}</strong></td>
            <td><a href="${img.file_path}" target="_blank" class="admin-btn btn-info">Open</a></td>
            <td>${uploadDate}</td>
            <td><a href="#" onclick="deleteImage(${img.id}); return false" class="admin-btn btn-danger">Delete</a></td>
        </tr>`;
    });
    html += '</tbody></table>';
    document.getElementById('images-list').innerHTML = html;
}

// Handle form submission
document.getElementById('image-upload-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const propertyId = document.getElementById('property-select').value;
    const imageInput = document.getElementById('image-input');
    const messageDiv = document.getElementById('upload-message');
    
    if (!propertyId) {
        messageDiv.style.display = 'block';
        messageDiv.innerHTML = '<p class="error-message">Please select a property</p>';
        return;
    }
    
    if (!imageInput.files[0]) {
        messageDiv.style.display = 'block';
        messageDiv.innerHTML = '<p class="error-message">Please select an image</p>';
        return;
    }
    
    const formData = new FormData();
    formData.append('property_id', propertyId);
    formData.append('image', imageInput.files[0]);
    
    fetch('/GuillaumeHousing/api/image/upload', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const messageDiv = document.getElementById('upload-message');
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = '<p class="success-message">Image uploaded successfully!</p>';
            document.getElementById('image-upload-form').reset();
            loadImages();
            setTimeout(() => {
                messageDiv.style.display = 'none';
            }, 3000);
        } else {
            const messageDiv = document.getElementById('upload-message');
            messageDiv.style.display = 'block';
            messageDiv.innerHTML = `<p class="error-message">${data.message}</p>`;
        }
    })
    .catch(e => {
        const messageDiv = document.getElementById('upload-message');
        messageDiv.style.display = 'block';
        messageDiv.innerHTML = '<p class="error-message">Error uploading image</p>';
    });
});

function deleteImage(id) {
    if (confirm('Delete this image?')) {
        fetch('/GuillaumeHousing/api/image/delete/' + id, { method: 'POST' })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    loadImages();
                } else {
                    alert('Error deleting image: ' + data.message);
                }
            })
            .catch(e => alert('Error deleting image'));
    }
}

// Initial load
loadImages();
</script>
