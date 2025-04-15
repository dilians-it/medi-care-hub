function openAddDoctorModal() {
    document.getElementById('addDoctorModal').classList.remove('hidden');
}

function closeAddDoctorModal() {
    document.getElementById('addDoctorModal').classList.add('hidden');
    document.getElementById('addDoctorForm').reset();
}

function openEditDoctorModal(doctor) {
    document.getElementById('edit_doctor_id').value = doctor.id;
    document.getElementById('edit_first_name').value = doctor.first_name;
    document.getElementById('edit_last_name').value = doctor.last_name;
    document.getElementById('edit_email').value = doctor.email;
    document.getElementById('edit_specialization').value = doctor.specialization;
    document.getElementById('edit_experience_years').value = doctor.experience_years;
    document.getElementById('editDoctorModal').classList.remove('hidden');
}

function closeEditDoctorModal() {
    document.getElementById('editDoctorModal').classList.add('hidden');
    document.getElementById('editDoctorForm').reset();
}

document.getElementById('addDoctorForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'add');
    
    fetch('../api/doctors.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        showAlert('Doctor added successfully');
        closeAddDoctorModal();
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error adding doctor', 'error');
    });
});

document.getElementById('editDoctorForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'edit');
    
    fetch('../api/doctors.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        showAlert('Doctor updated successfully');
        closeEditDoctorModal();
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error updating doctor', 'error');
    });
});

function editDoctor(doctorId) {
    fetch(`../api/doctors.php?action=get&doctor_id=${doctorId}`)
        .then(response => response.json())
        .then(doctor => {
            openEditDoctorModal(doctor);
        })
        .catch(error => {
            showAlert('Error fetching doctor details', 'error');
        });
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
