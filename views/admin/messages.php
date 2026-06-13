<?php
// Admin messages management
?>
<h1>Messages / Inquiries</h1>

<div id="messages-list">
    <p>Loading messages...</p>
</div>

<script>
fetch('/GuillaumeHousing/api/messages')
    .then(r => r.json())
    .then(messages => {
        let html = '<table style="width:100%;border-collapse:collapse">';
        html += '<thead><tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Date</th><th>Actions</th></tr></thead>';
        html += '<tbody>';
        messages.forEach(m => {
            html += `<tr style="border-bottom:1px solid #ddd">
                <td>${m.id}</td>
                <td>${m.name}</td>
                <td>${m.email}</td>
                <td>${m.subject}</td>
                <td style="max-width:200px;overflow:hidden;text-overflow:ellipsis">${m.message.substring(0, 50)}...</td>
                <td>${new Date(m.created_at).toLocaleDateString()}</td>
                <td>
                    <a href="#" onclick="viewMessage(${m.id}, '${m.name.replace(/'/g, "\\'")}', '${m.email}', '${m.subject.replace(/'/g, "\\'")}', '${m.message.replace(/'/g, "\\'").replace(/\n/g, "\\n")}'); return false">View</a> |
                    <a href="#" onclick="deleteMessage(${m.id}); return false">Delete</a>
                </td>
            </tr>`;
        });
        html += '</tbody></table>';
        document.getElementById('messages-list').innerHTML = html;
    })
    .catch(e => {
        document.getElementById('messages-list').innerHTML = '<p>Error loading messages</p>';
    });

function viewMessage(id, name, email, subject, message) {
    alert(`From: ${name} (${email})\nSubject: ${subject}\n\n${message}`);
}

function deleteMessage(id) {
    if (confirm('Delete this message?')) {
        fetch('/GuillaumeHousing/api/message/delete/' + id, { method: 'POST' })
            .then(() => location.reload())
            .catch(e => alert('Error deleting message'));
    }
}
</script>
