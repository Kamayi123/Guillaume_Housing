<?php
// Property create/edit form
$isEdit = isset($property);
$formTitle = $isEdit ? 'Edit Property' : 'Add New Property';
$formAction = $isEdit ? "/GuillaumeHousing/api/property/update/{$property['id']}" : '/GuillaumeHousing/api/property/create';
?>
<h1><?php echo $formTitle; ?></h1>

<form id="property-form" class="admin-form" enctype="multipart/form-data">
    <div class="admin-form-group">
        <label>Title:</label>
        <input type="text" name="title" value="<?php echo $isEdit ? htmlspecialchars($property['title']) : ''; ?>" required>
    </div>
    
    <div class="admin-form-group">
        <label>Description:</label>
        <textarea name="description" rows="4"><?php echo $isEdit ? htmlspecialchars($property['description']) : ''; ?></textarea>
    </div>
    
    <div class="admin-form-group">
        <label>Price (FCFA):</label>
        <input type="number" name="price" value="<?php echo $isEdit ? $property['price'] : ''; ?>" step="1" required>
    </div>
    
    <div class="admin-form-group">
        <label>Location:</label>
        <input type="text" name="location" value="<?php echo $isEdit ? htmlspecialchars($property['location']) : ''; ?>" required>
    </div>
    
    <div class="admin-form-group">
        <label>Bedrooms:</label>
        <input type="number" name="bedrooms" value="<?php echo $isEdit ? $property['bedrooms'] : ''; ?>" required>
    </div>
    
    <div class="admin-form-group">
        <label>Bathrooms:</label>
        <input type="number" name="bathrooms" value="<?php echo $isEdit ? $property['bathrooms'] : ''; ?>" required>
    </div>
    
    <div class="admin-form-group">
        <label>Area (sq ft):</label>
        <input type="number" name="area" value="<?php echo $isEdit ? $property['area'] : ''; ?>" required>
    </div>
    
    <div class="admin-form-group">
        <label>Type:</label>
        <select name="type">
            <option value="Residential" <?php echo ($isEdit && $property['type'] === 'Residential') ? 'selected' : ''; ?>>Residential</option>
            <option value="Commercial" <?php echo ($isEdit && $property['type'] === 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
        </select>
    </div>
    
    <div class="admin-form-group">
        <label>Status:</label>
        <select name="status">
            <option value="available" <?php echo ($isEdit && $property['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
            <option value="for-rent" <?php echo ($isEdit && $property['status'] === 'for-rent') ? 'selected' : ''; ?>>For Rent</option>
            <option value="for-sale" <?php echo ($isEdit && $property['status'] === 'for-sale') ? 'selected' : ''; ?>>For Sale</option>
            <option value="rented" <?php echo ($isEdit && $property['status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
            <option value="sold" <?php echo ($isEdit && $property['status'] === 'sold') ? 'selected' : ''; ?>>Sold</option>
        </select>
    </div>
    
    <div class="admin-form-group">
        <label>Property Images:</label>
        <input type="file" name="images[]" multiple accept="image/*">
        <small>Select multiple images (JPG, PNG). First image will be primary.</small>
    </div>
    
    <?php if ($isEdit && !empty($property['image'])): ?>
    <div class="admin-form-group">
        <label>Current Image:</label>
        <img src="<?php echo htmlspecialchars($property['image']); ?>" class="current-image">
    </div>
    <?php endif; ?>
    
    <button type="submit" class="admin-form-submit">Save Property</button>
    <a href="/GuillaumeHousing/admin/properties">Cancel</a>
</form>

<script>
document.getElementById('property-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.textContent = 'Saving...';
    
    console.log('Submitting form to: <?php echo $formAction; ?>');
    
    fetch('<?php echo $formAction; ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => {
        console.log('Response status:', r.status);
        const contentType = r.headers.get('content-type');
        
        if (contentType && contentType.includes('application/json')) {
            return r.json().then(data => {
                if (!r.ok) {
                    throw new Error(data.message || 'HTTP ' + r.status);
                }
                return data;
            });
        } else {
            return r.text().then(text => {
                throw new Error('Invalid response type. Expected JSON, got: ' + contentType + '\n' + text.substring(0, 500));
            });
        }
    })
    .then(data => {
        console.log('Response data:', data);
        if (data.success) {
            alert('Property saved successfully');
            window.location.href = '/GuillaumeHousing/admin/properties';
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        }
    })
    .catch(e => {
        console.error('Fetch error:', e);
        alert('Error saving property: ' + e.message);
        submitBtn.disabled = false;
        submitBtn.textContent = originalText;
    });
});
</script>
