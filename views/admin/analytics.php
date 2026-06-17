<?php
// Admin analytics and reports
?>
<h1>Analytics & Reports</h1>

<h2>Overview</h2>
<div class="stats-flex">
    <div class="stat-item">
        <h3 id="total-revenue" class="stat-value">Loading...</h3>
        <p>Total Revenue</p>
    </div>
    <div class="stat-item">
        <h3 id="avg-booking" class="stat-value">Loading...</h3>
        <p>Avg. Booking Value</p>
    </div>
    <div class="stat-item">
        <h3 id="occupancy" class="stat-value">Loading...</h3>
        <p>Occupancy Rate</p>
    </div>
</div>

<div class="admin-card">
    <h2>Bookings Over Time</h2>
    <canvas id="bookings-chart" class="admin-canvas"></canvas>
</div>

<div class="admin-card">
    <h2>Export Data</h2>
    <button onclick="exportBookings()" class="admin-btn btn-primary">Export Bookings PDF</button>
    <button onclick="exportProperties()" class="admin-btn btn-primary">Export Properties PDF</button>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
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
</script>
