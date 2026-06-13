<?php
// Property create/edit form
$isEdit = isset($property);
$formTitle = $isEdit ? 'Edit Property' : 'Add New Property';
$formAction = $isEdit ? "/GuillaumeHousing/api/property/update/{$property['id']}" : '/GuillaumeHousing/api/property/create';
?>
<h1><?php echo $formTitle; ?></h1>

<form id="property-form" enctype="multipart/form-data" style="max-width:600px">
    <div style="margin-bottom:12px">
        <label>Title:</label><br>
        <input type="text" name="title" value="<?php echo $isEdit ? htmlspecialchars($property['title']) : ''; ?>" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Description:</label><br>
        <textarea name="description" rows="4" style="width:100%;padding:8px"><?php echo $isEdit ? htmlspecialchars($property['description']) : ''; ?></textarea>
    </div>
    
    <div style="margin-bottom:12px">
        <label>Price:</label><br>
        <input type="number" name="price" value="<?php echo $isEdit ? $property['price'] : ''; ?>" step="0.01" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Location:</label><br>
        <input type="text" name="location" value="<?php echo $isEdit ? htmlspecialchars($property['location']) : ''; ?>" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Bedrooms:</label><br>
        <input type="number" name="bedrooms" value="<?php echo $isEdit ? $property['bedrooms'] : ''; ?>" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Bathrooms:</label><br>
        <input type="number" name="bathrooms" value="<?php echo $isEdit ? $property['bathrooms'] : ''; ?>" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Area (sq ft):</label><br>
        <input type="number" name="area" value="<?php echo $isEdit ? $property['area'] : ''; ?>" required style="width:100%;padding:8px">
    </div>
    
    <div style="margin-bottom:12px">
        <label>Type:</label><br>
        <select name="type" style="width:100%;padding:8px">
            <option value="Residential" <?php echo ($isEdit && $property['type'] === 'Residential') ? 'selected' : ''; ?>>Residential</option>
            <option value="Commercial" <?php echo ($isEdit && $property['type'] === 'Commercial') ? 'selected' : ''; ?>>Commercial</option>
        </select>
    </div>
    
    <div style="margin-bottom:12px">
        <label>Status:</label><br>
        <select name="status" style="width:100%;padding:8px">
            <option value="available" <?php echo ($isEdit && $property['status'] === 'available') ? 'selected' : ''; ?>>Available</option>
            <option value="for-rent" <?php echo ($isEdit && $property['status'] === 'for-rent') ? 'selected' : ''; ?>>For Rent</option>
            <option value="for-sale" <?php echo ($isEdit && $property['status'] === 'for-sale') ? 'selected' : ''; ?>>For Sale</option>
            <option value="rented" <?php echo ($isEdit && $property['status'] === 'rented') ? 'selected' : ''; ?>>Rented</option>
            <option value="sold" <?php echo ($isEdit && $property['status'] === 'sold') ? 'selected' : ''; ?>>Sold</option>
        </select>
    </div>
    
    <div style="margin-bottom:12px">
        <label><input type="checkbox" name="is_featured" value="1" <?php echo ($isEdit && isset($property['is_featured']) && $property['is_featured']) ? 'checked' : ''; ?>> Featured Property</label>
    </div>
    
    <div style="margin-bottom:12px">
        <label>Property Images:</label><br>
        <input type="file" name="images[]" multiple accept="image/*" style="width:100%;padding:8px">
        <small>Select multiple images (JPG, PNG). First image will be primary.</small>
    </div>
    
    <?php if ($isEdit && !empty($property['image'])): ?>
    <div style="margin-bottom:12px">
        <label>Current Image:</label><br>
        <img src="<?php echo htmlspecialchars($property['image']); ?>" style="max-width:200px">
    </div>
    <?php endif; ?>
    
    <button type="submit" style="padding:10px 20px;background:#28a745;color:#fff;border:none;border-radius:4px;cursor:pointer">Save Property</button>
    <a href="/GuillaumeHousing/admin/properties" style="margin-left:10px">Cancel</a>
</form>

<script>
document.getElementById('property-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    
    fetch('<?php echo $formAction; ?>', {
        method: 'POST',
        body: formData
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            alert('Property saved successfully');
            window.location.href = '/GuillaumeHousing/admin/properties';
        } else {
            alert('Error: ' + (data.message || 'Unknown error'));
        }
    })
    .catch(e => alert('Error saving property'));
});
</script>
