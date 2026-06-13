<?php
// Admin analytics and reports
?>
<h1>Analytics & Reports</h1>

<div style="margin-bottom:30px">
    <h2>Overview</h2>
    <div id="overview-stats" style="display:flex;gap:20px">
        <div style="padding:20px;background:#f5f5f5;border-radius:4px;flex:1">
            <h3 id="total-revenue">Loading...</h3>
            <p>Total Revenue</p>
        </div>
        <div style="padding:20px;background:#f5f5f5;border-radius:4px;flex:1">
            <h3 id="avg-booking">Loading...</h3>
            <p>Avg. Booking Value</p>
        </div>
        <div style="padding:20px;background:#f5f5f5;border-radius:4px;flex:1">
            <h3 id="occupancy">Loading...</h3>
            <p>Occupancy Rate</p>
        </div>
    </div>
</div>

<div style="margin-bottom:30px">
    <h2>Bookings Over Time</h2>
    <canvas id="bookings-chart" style="max-width:800px;max-height:400px"></canvas>
</div>

<div style="margin-bottom:30px">
    <h2>Export Data</h2>
    <button onclick="exportBookings()" style="padding:8px 16px;background:#007bff;color:#fff;border:none;border-radius:4px;cursor:pointer">Export Bookings CSV</button>
    <button onclick="exportProperties()" style="padding:8px 16px;background:#28a745;color:#fff;border:none;border-radius:4px;cursor:pointer;margin-left:10px">Export Properties CSV</button>
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
