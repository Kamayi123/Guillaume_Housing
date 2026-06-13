<?php
// Admin bookings management
?>
<h1>Bookings Management</h1>

<div style="margin-bottom:20px">
    <label style="font-weight:bold">Filter by status:&nbsp;&nbsp;</label>
    <select id="status-filter" style="padding:8px;border:1px solid #ddd;border-radius:4px" onchange="filterBookings()">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="cancelled">Cancelled</option>
        <option value="completed">Completed</option>
    </select>
</div>

<div id="bookings-list" style="background:#fff;border-radius:4px;box-shadow:0 2px 4px rgba(0,0,0,0.1);overflow:hidden">
    <p style="padding:20px">Loading bookings...</p>
</div>

<script>
let allBookings = [];

fetch('/GuillaumeHousing/api/bookings')
    .then(r => r.json())
    .then(bookings => {
        allBookings = bookings;
        renderBookings(bookings);
    })
    .catch(e => {
        document.getElementById('bookings-list').innerHTML = '<p style="padding:20px;color:#e74c3c">Error loading bookings</p>';
    });

function renderBookings(bookings) {
    let html = '<table style="width:100%;margin:0;border:none">';
    html += '<thead style="background:#f5f5f5"><tr><th>ID</th><th>Property</th><th>Check-in</th><th>Check-out</th><th>Guests</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>';
    html += '<tbody>';
    bookings.forEach(b => {
        const statusColor = b.status === 'confirmed' ? '#27ae60' : (b.status === 'cancelled' ? '#e74c3c' : '#f39c12');
        html += `<tr>
            <td>${b.id}</td>
            <td><strong>${b.property_title || 'Property #' + b.property_id}</strong></td>
            <td>${b.check_in}</td>
            <td>${b.check_out}</td>
            <td>${b.guests}</td>
            <td>FCFA ${ Number(b.total_price).toLocaleString()}</td>
            <td><span style="background:${statusColor};color:#fff;padding:4px 8px;border-radius:3px;font-size:12px">${b.status.toUpperCase()}</span></td>
            <td style="font-size:12px">
                <select onchange="updateStatus(${b.id}, this.value)" style="padding:4px;border:1px solid #ddd;border-radius:3px">
                    <option value="">Update...</option>
                    <option value="confirmed">Confirm</option>
                    <option value="cancelled">Cancel</option>
                    <option value="completed">Complete</option>
                </select> |
                <a href="#" onclick="deleteBooking(${b.id}); return false" style="color:#e74c3c;text-decoration:none;margin-left:8px">Delete</a>
            </td>
        </tr>`;
    });
    html += '</tbody></table>';
    document.getElementById('bookings-list').innerHTML = html;
}

function filterBookings() {
    const status = document.getElementById('status-filter').value;
    const filtered = status ? allBookings.filter(b => b.status === status) : allBookings;
    renderBookings(filtered);
}

function updateStatus(id, status) {
    if (status) {
        fetch('/GuillaumeHousing/api/booking/update/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: status })
        })
        .then(() => location.reload())
        .catch(e => alert('Error updating booking'));
    }
}

function deleteBooking(id) {
    if (confirm('Delete this booking?')) {
        fetch('/GuillaumeHousing/api/booking/delete/' + id, { method: 'POST' })
            .then(() => location.reload())
            .catch(e => alert('Error deleting booking'));
    }
}
</script>
