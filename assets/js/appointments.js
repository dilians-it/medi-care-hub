document.getElementById('appointmentForm').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    fetch('../api/appointments.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        showAlert('Appointment booked successfully!');
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error booking appointment', 'error');
    });
});
