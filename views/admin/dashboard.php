<?php
// Admin dashboard with statistics and analytics
?>
<h1>Dashboard</h1>
<div class="stats-grid">
    <div class="stat-card border-blue">
        <div class="stat-card-content">
            <svg data-lucide="home" class="stat-card-icon blue"></svg>
            <div>
                <h3 id="prop-count">-</h3>
                <p>Properties</p>
            </div>
        </div>
    </div>
    <div class="stat-card border-green">
        <div class="stat-card-content">
            <svg data-lucide="calendar" class="stat-card-icon green"></svg>
            <div>
                <h3 id="booking-count">-</h3>
                <p>Bookings</p>
            </div>
        </div>
    </div>
    <div class="stat-card border-red">
        <div class="stat-card-content">
            <svg data-lucide="mail" class="stat-card-icon red"></svg>
            <div>
                <h3 id="msg-count">-</h3>
                <p>Messages</p>
            </div>
        </div>
    </div>
    <div class="stat-card border-orange">
        <div class="stat-card-content">
            <svg data-lucide="users" class="stat-card-icon orange"></svg>
            <div>
                <h3 id="user-count">-</h3>
                <p>Users</p>
            </div>
        </div>
    </div>
</div>

<h2>Analytics Overview</h2>
<div class="stats-flex">
    <div class="stat-item border-green">
        <div class="stat-overview">
            <svg data-lucide="dollar-sign" class="icon-green"></svg>
            <p>Total Revenue</p>
        </div>
        <h3 id="total-revenue" class="stat-value">Loading...</h3>
    </div>
    <div class="stat-item border-blue">
        <div class="stat-overview">
            <svg data-lucide="trending-up" class="icon-blue"></svg>
            <p>Avg. Booking Value</p>
        </div>
        <h3 id="avg-booking" class="stat-value">Loading...</h3>
    </div>
    <div class="stat-item border-orange">
        <div class="stat-overview">
            <svg data-lucide="percent" class="icon-orange"></svg>
            <p>Occupancy Rate</p>
        </div>
        <h3 id="occupancy" class="stat-value">Loading...</h3>
    </div>
</div>

<div class="admin-card">
    <div class="card-header">
        <svg data-lucide="line-chart" class="icon-dark-blue"></svg>
        <h2>Bookings Over Time</h2>
    </div>
    <canvas id="bookings-chart" class="admin-canvas"></canvas>
</div>

<div class="admin-card">
    <div class="card-header">
        <svg data-lucide="download" class="icon-gray"></svg>
        <h2>Export Data</h2>
    </div>
    <p class="card-subtext">Download reports for further analysis:</p>
    <div class="card-buttons">
        <button onclick="exportBookings()" class="admin-btn"><svg data-lucide="file-pdf" class="icon-small icon-gray"></svg>Bookings</button>
        <button onclick="exportProperties()" class="admin-btn"><svg data-lucide="file-pdf" class="icon-small icon-gray"></svg>Properties</button>
        <button onclick="exportMessages()" class="admin-btn"><svg data-lucide="file-pdf" class="icon-small icon-gray"></svg>Messages</button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
fetch('/GuillaumeHousing/api/admin/stats')
  .then(r => r.json())
  .then(data => {
    document.getElementById('prop-count').innerText = data.total_properties;
    document.getElementById('booking-count').innerText = data.total_bookings;
    document.getElementById('msg-count').innerText = data.total_messages;
    document.getElementById('user-count').innerText = data.total_users;
  })
  .catch(e => console.error('Error loading stats:', e));

// Fetch analytics data
fetch('/GuillaumeHousing/api/admin/analytics')
    .then(r => r.json())
    .then(data => {
        document.getElementById('total-revenue').innerText = 'FCFA ' + Number(data.total_revenue || 0).toLocaleString();
        document.getElementById('avg-booking').innerText = 'FCFA ' + Number(data.avg_booking_value || 0).toLocaleString();
        document.getElementById('occupancy').innerText = (data.occupancy_rate || 0) + '%';
        
        // Render chart
        if (data.bookings_timeline) {
            const ctx = document.getElementById('bookings-chart').getContext('2d');
            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: data.bookings_timeline.map(d => d.date),
                    datasets: [{
                        label: 'Bookings',
                        data: data.bookings_timeline.map(d => d.count),
                        borderColor: '#007bff',
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        y: { beginAtZero: true }
                    }
                }
            });
        }
    })
    .catch(e => console.error('Error loading analytics:', e));

function exportBookings() {
    window.location.href = '/GuillaumeHousing/api/admin/export/bookings';
}

function exportProperties() {
    window.location.href = '/GuillaumeHousing/api/admin/export/properties';
}

function exportMessages() {
    window.location.href = '/GuillaumeHousing/api/admin/export/messages';
}
</script>
