document.querySelectorAll('.approve-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        updateAppointmentStatus(this.dataset.id, 'approved');
    });
});

document.querySelectorAll('.reject-btn').forEach(btn => {
    btn.addEventListener('click', function() {
        updateAppointmentStatus(this.dataset.id, 'rejected');
    });
});

function updateAppointmentStatus(appointmentId, status) {
    fetch('../api/appointments.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `action=update_status&appointment_id=${appointmentId}&status=${status}`
    })
    .then(response => response.text())
    .then(() => {
        showAlert('Appointment updated successfully!');
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error updating appointment', 'error');
    });
}
