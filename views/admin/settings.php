<?php
// Admin settings view
?>
<h1>Settings</h1>

<div style="max-width:600px">
    <h2>Site Configuration</h2>
    <form id="settings-form">
        <div style="margin-bottom:12px">
            <label>Site Name:</label><br>
            <input type="text" name="site_name" value="Guillaume Housing" style="width:100%;padding:8px">
        </div>
        
        <div style="margin-bottom:12px">
            <label>Contact Email:</label><br>
            <input type="email" name="contact_email" value="kamayiguillaume@gmail.com" style="width:100%;padding:8px">
        </div>
        
        <div style="margin-bottom:12px">
            <label>Contact Phone:</label><br>
            <input type="text" name="contact_phone" value="+237 653901025" style="width:100%;padding:8px">
        </div>
        
        <div style="margin-bottom:12px">
            <label>Site Address:</label><br>
            <input type="text" name="site_address" value="Molyko, Buea, Cameroon" style="width:100%;padding:8px">
        </div>
        
        <div style="margin-bottom:12px">
            <label><input type="checkbox" name="maintenance_mode"> Enable Maintenance Mode</label>
        </div>
        
        <button type="submit" style="padding:10px 20px;background:#28a745;color:#fff;border:none;border-radius:4px;cursor:pointer">Save Settings</button>
    </form>
</div>

<script>
document.getElementById('settings-form').addEventListener('submit', function(e) {
    e.preventDefault();
    alert('Settings saving functionality would be implemented here.');
});
</script>
