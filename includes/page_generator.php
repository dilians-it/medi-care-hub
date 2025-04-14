<?php
function generatePage($role, $page, $title) {
    $dir = "../$role";
    if (!file_exists($dir)) {
        mkdir($dir, 0777, true);
    }

    $template = <<<EOT
<?php
session_start();
require_once '../config/database.php';
require_once '../includes/utils.php';
require_once '../includes/header.php';

if (\$_SESSION['role'] !== '$role') {
    header('Location: ../index.php');
    exit();
}

// Page-specific logic here
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6">$title</h2>
    <div class="bg-white rounded-lg shadow-md p-6">
        <!-- Page content here -->
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>
EOT;

    file_put_contents("$dir/$page.php", $template);
}

// Generate admin pages
$admin_pages = [
    'hospitals' => 'Manage Hospitals',
    'doctors' => 'Manage Doctors',
    'patients' => 'View Patients',
    'reports' => 'System Reports'
];

foreach ($admin_pages as $page => $title) {
    generatePage('admin', $page, $title);
}

// Generate hospital pages
$hospital_pages = [
    'doctors' => 'Manage Doctors',
    'appointments' => 'View Appointments',
    'feed' => 'Hospital Feed',
    'chat' => 'Messages'
];

foreach ($hospital_pages as $page => $title) {
    generatePage('hospital', $page, $title);
}

// Generate doctor pages
$doctor_pages = [
    'appointments' => 'My Appointments',
    'patients' => 'My Patients',
    'reports' => 'Patient Reports',
    'chat' => 'Messages'
];

foreach ($doctor_pages as $page => $title) {
    generatePage('doctor', $page, $title);
}

// Generate patient pages
$patient_pages = [
    'appointments' => 'Book Appointments',
    'reports' => 'Medical Reports',
    'feed' => 'Hospital Updates',
    'chat' => 'Messages',
    'chatbot' => 'AI Assistant'
];

foreach ($patient_pages as $page => $title) {
    generatePage('patient', $page, $title);
}

echo "Pages generated successfully!";
