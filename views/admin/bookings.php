<?php
// Admin bookings management
?>
<h1>Bookings Management</h1>

<div class="admin-filter-group">
    <label>Filter by status:&nbsp;&nbsp;</label>
    <select id="status-filter" onchange="filterBookings()">
        <option value="">All</option>
        <option value="pending">Pending</option>
        <option value="confirmed">Confirmed</option>
        <option value="cancelled">Cancelled</option>
        <option value="completed">Completed</option>
    </select>
</div>

<div id="bookings-list" class="admin-card">
    <p class="load-message">Loading bookings...</p>
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
        document.getElementById('bookings-list').innerHTML = '<p class="error-message">Error loading bookings</p>';
    });

function renderBookings(bookings) {
    let html = '<table class="admin-table">';
    html += '<thead><tr><th>ID</th><th>Property</th><th>Check-in</th><th>Check-out</th><th>Guests</th><th>Total</th><th>Status</th><th>Actions</th></tr></thead>';
    html += '<tbody>';
    bookings.forEach(b => {
        const statusClass = 'status-' + b.status;
        html += `<tr>
            <td>${b.id}</td>
            <td><strong>${b.property_title || 'Property #' + b.property_id}</strong></td>
            <td>${b.check_in}</td>
            <td>${b.check_out}</td>
            <td>${b.guests}</td>
            <td>FCFA ${ Number(b.total_price).toLocaleString()}</td>
            <td><span class="status-badge ${statusClass}">${b.status.toUpperCase()}</span></td>
            <td><button onclick="confirmBooking(${b.id})" class="admin-btn btn-primary">Confirm</button> <a href="#" onclick="deleteBooking(${b.id}); return false" class="admin-btn btn-danger">Delete</a></td>
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

function confirmBooking(id) {
    if (confirm('Confirm this booking?')) {
        fetch('/GuillaumeHousing/api/booking/update/' + id, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ status: 'confirmed' })
        })
        .then(r => {
            console.log('Response status:', r.status);
            if (r.ok) return r.json();
            else return r.json().then(e => { throw new Error(e.message || 'Error confirming booking'); });
        })
        .then(data => {
            console.log('Confirmation successful:', data);
            location.reload();
        })
        .catch(e => {
            console.error('Error:', e.message);
            alert('Error confirming booking: ' + e.message);
        });
    }
}

function deleteBooking(id) {
    if (confirm('Delete this booking?')) {
        fetch('/GuillaumeHousing/api/booking/delete/' + id, { method: 'POST' })
            .then(r => {
                console.log('Delete response status:', r.status);
                if (r.ok) return r.json();
                else return r.json().then(e => { throw new Error(e.message || 'Error deleting booking'); });
            })
            .then(data => {
                console.log('Deletion successful:', data);
                location.reload();
            })
            .catch(e => {
                console.error('Error:', e.message);
                alert('Error deleting booking: ' + e.message);
            });
    }
}
</script>
