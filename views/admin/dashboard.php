<?php
// Admin dashboard with statistics and analytics
?>
<h1>Dashboard</h1>
<div id="stats" style="display:grid;grid-template-columns:repeat(4,1fr);gap:20px;margin-bottom:30px">
    <div style="background:#fff;padding:20px;border-radius:4px;border-left:4px solid #3498db;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
        <div style="display:flex;align-items:center;gap:12px">
            <svg data-lucide="home" style="width:32px;height:32px;color:#3498db;flex-shrink:0"></svg>
            <div>
                <h3 style="margin:0;font-size:28px" id="prop-count">-</h3>
                <p style="margin:5px 0 0 0;color:#666;font-size:12px">Properties</p>
            </div>
        </div>
    </div>
    <div style="background:#fff;padding:20px;border-radius:4px;border-left:4px solid #27ae60;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
        <div style="display:flex;align-items:center;gap:12px">
            <svg data-lucide="calendar" style="width:32px;height:32px;color:#27ae60;flex-shrink:0"></svg>
            <div>
                <h3 style="margin:0;font-size:28px" id="booking-count">-</h3>
                <p style="margin:5px 0 0 0;color:#666;font-size:12px">Bookings</p>
            </div>
        </div>
    </div>
    <div style="background:#fff;padding:20px;border-radius:4px;border-left:4px solid #e74c3c;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
        <div style="display:flex;align-items:center;gap:12px">
            <svg data-lucide="mail" style="width:32px;height:32px;color:#e74c3c;flex-shrink:0"></svg>
            <div>
                <h3 style="margin:0;font-size:28px" id="msg-count">-</h3>
                <p style="margin:5px 0 0 0;color:#666;font-size:12px">Messages</p>
            </div>
        </div>
    </div>
    <div style="background:#fff;padding:20px;border-radius:4px;border-left:4px solid #f39c12;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
        <div style="display:flex;align-items:center;gap:12px">
            <svg data-lucide="users" style="width:32px;height:32px;color:#f39c12;flex-shrink:0"></svg>
            <div>
                <h3 style="margin:0;font-size:28px" id="user-count">-</h3>
                <p style="margin:5px 0 0 0;color:#666;font-size:12px">Users</p>
            </div>
        </div>
    </div>
</div>

<div style="background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1);margin-bottom:30px">
    <h2>Recent Activity</h2>
    <p style="color:#666">Quick access to admin tools:</p>
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:10px">
        <a href="/GuillaumeHousing/admin/properties" style="padding:12px;background:#3498db;color:#fff;text-decoration:none;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:500"><svg data-lucide="home" style="width:16px;height:16px"></svg>Manage Properties</a>
        <a href="/GuillaumeHousing/admin/bookings" style="padding:12px;background:#27ae60;color:#fff;text-decoration:none;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:500"><svg data-lucide="calendar" style="width:16px;height:16px"></svg>View Bookings</a>
        <a href="/GuillaumeHousing/admin/messages" style="padding:12px;background:#e74c3c;color:#fff;text-decoration:none;border-radius:4px;text-align:center;display:flex;align-items:center;justify-content:center;gap:8px;font-weight:500"><svg data-lucide="mail" style="width:16px;height:16px"></svg>Check Messages</a>
    </div>
</div>

<div style="margin-bottom:30px">
    <h2>Analytics Overview</h2>
    <div id="overview-stats" style="display:flex;gap:20px">
        <div style="padding:20px;background:#fff;border-radius:4px;flex:1;box-shadow:0 2px 4px rgba(0,0,0,0.1);border-left:4px solid #27ae60">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <svg data-lucide="dollar-sign" style="width:20px;height:20px;color:#27ae60"></svg>
                <p style="margin:0;color:#666;font-size:12px">Total Revenue</p>
            </div>
            <h3 id="total-revenue" style="margin:0;font-size:24px">Loading...</h3>
        </div>
        <div style="padding:20px;background:#fff;border-radius:4px;flex:1;box-shadow:0 2px 4px rgba(0,0,0,0.1);border-left:4px solid #3498db">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <svg data-lucide="trending-up" style="width:20px;height:20px;color:#3498db"></svg>
                <p style="margin:0;color:#666;font-size:12px">Avg. Booking Value</p>
            </div>
            <h3 id="avg-booking" style="margin:0;font-size:24px">Loading...</h3>
        </div>
        <div style="padding:20px;background:#fff;border-radius:4px;flex:1;box-shadow:0 2px 4px rgba(0,0,0,0.1);border-left:4px solid #f39c12">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:10px">
                <svg data-lucide="percent" style="width:20px;height:20px;color:#f39c12"></svg>
                <p style="margin:0;color:#666;font-size:12px">Occupancy Rate</p>
            </div>
            <h3 id="occupancy" style="margin:0;font-size:24px">Loading...</h3>
        </div>
    </div>
</div>

<div style="background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1);margin-bottom:30px">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:20px">
        <svg data-lucide="line-chart" style="width:24px;height:24px;color:#007bff"></svg>
        <h2 style="margin:0">Bookings Over Time</h2>
    </div>
    <canvas id="bookings-chart" style="max-width:100%;max-height:400px"></canvas>
</div>

<div style="background:#fff;padding:20px;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1)">
    <div style="display:flex;align-items:center;gap:10px;margin-bottom:15px">
        <svg data-lucide="download" style="width:24px;height:24px;color:#28a745"></svg>
        <h2 style="margin:0">Export Data</h2>
    </div>
    <p style="color:#666;margin-bottom:15px">Download reports for further analysis:</p>
    <button onclick="exportBookings()" style="padding:10px 20px;background:#007bff;color:#fff;border:none;border-radius:4px;cursor:pointer;font-size:14px;display:flex;align-items:center;gap:8px"><svg data-lucide="file-csv" style="width:16px;height:16px"></svg>Export Bookings CSV</button>
    <button onclick="exportProperties()" style="padding:10px 20px;background:#28a745;color:#fff;border:none;border-radius:4px;cursor:pointer;margin-left:10px;font-size:14px;display:flex;align-items:center;gap:8px"><svg data-lucide="file-csv" style="width:16px;height:16px"></svg>Export Properties CSV</button>
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
</script>
