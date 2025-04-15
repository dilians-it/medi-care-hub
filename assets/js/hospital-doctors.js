function openAddDoctorModal() {
    // Implement modal for adding new doctor
}

function editDoctor(doctorId) {
    // Implement doctor editing functionality
}

function deleteDoctor(doctorId) {
    if (confirm('Are you sure you want to remove this doctor?')) {
        fetch('../api/doctors.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&doctor_id=${doctorId}`
        })
        .then(response => response.text())
        .then(() => {
            showAlert('Doctor removed successfully');
            setTimeout(() => window.location.reload(), 2000);
        })
        .catch(error => {
            showAlert('Error removing doctor', 'error');
        });
    }
}
