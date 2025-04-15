function openAddHospitalModal() {
    document.getElementById('addHospitalModal').classList.remove('hidden');
}

function closeAddHospitalModal() {
    document.getElementById('addHospitalModal').classList.add('hidden');
    document.getElementById('addHospitalForm').reset();
}

function openEditHospitalModal(hospital) {
    document.getElementById('edit_hospital_id').value = hospital.id;
    document.getElementById('edit_name').value = hospital.name;
    document.getElementById('edit_email').value = hospital.email;
    document.getElementById('edit_address').value = hospital.address;
    document.getElementById('edit_phone').value = hospital.phone;
    document.getElementById('editHospitalModal').classList.remove('hidden');
}

function closeEditHospitalModal() {
    document.getElementById('editHospitalModal').classList.add('hidden');
    document.getElementById('editHospitalForm').reset();
}

document.getElementById('addHospitalForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'add');
    
    fetch('../api/hospitals.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        showAlert('Hospital added successfully');
        closeAddHospitalModal();
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error adding hospital', 'error');
    });
});

document.getElementById('editHospitalForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    formData.append('action', 'edit');
    
    fetch('../api/hospitals.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.text())
    .then(result => {
        showAlert('Hospital updated successfully');
        closeEditHospitalModal();
        setTimeout(() => window.location.reload(), 2000);
    })
    .catch(error => {
        showAlert('Error updating hospital', 'error');
    });
});

function editHospital(hospitalId) {
    fetch(`../api/hospitals.php?action=get&hospital_id=${hospitalId}`)
        .then(response => response.json())
        .then(hospital => {
            openEditHospitalModal(hospital);
        })
        .catch(error => {
            showAlert('Error fetching hospital details', 'error');
        });
}

function deleteHospital(hospitalId) {
    if (confirm('Are you sure you want to delete this hospital?')) {
        fetch('../api/hospitals.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `action=delete&hospital_id=${hospitalId}`
        })
        .then(response => response.text())
        .then(() => {
            showAlert('Hospital deleted successfully');
            setTimeout(() => window.location.reload(), 2000);
        })
        .catch(error => {
            showAlert('Error deleting hospital', 'error');
        });
    }
}
