<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    exit('Unauthorized');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $doctorId = $_POST['doctor_id'];
    $appointmentDate = $_POST['appointment_date'];
    $reason = $_POST['reason'];
    
    // Get patient ID
    $stmt = $pdo->prepare("SELECT id FROM patients WHERE user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $patientId = $stmt->fetch()['id'];
    
    // Create appointment
    $stmt = $pdo->prepare("
        INSERT INTO appointments (doctor_id, patient_id, appointment_date, reason, status) 
        VALUES (?, ?, ?, ?, 'pending')
    ");
    $stmt->execute([$doctorId, $patientId, $appointmentDate, $reason]);
    
    // Create notification for doctor
    $stmt = $pdo->prepare("SELECT user_id FROM doctors WHERE id = ?");
    $stmt->execute([$doctorId]);
    $doctorUserId = $stmt->fetch()['user_id'];
    
    createNotification(
        $pdo,
        $doctorUserId,
        'New Appointment Request',
        'You have a new appointment request',
        'appointment'
    );
    
    exit('Appointment created');
}
