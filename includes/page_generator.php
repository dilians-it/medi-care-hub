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

if (!isset(\$_SESSION['user_id']) || \$_SESSION['role'] !== '$role') {
    header('Location: ../index.php');
    exit();
}

// Get user data
\$userId = \$_SESSION['user_id'];
\$stmt = \$pdo->prepare("SELECT * FROM users WHERE id = ?");
\$stmt->execute([\$userId]);
\$user = \$stmt->fetch();

// Page-specific data loading
switch('$page') {
    case 'appointments':
        \$stmt = \$pdo->prepare("
            SELECT a.*, d.first_name as doctor_name, p.first_name as patient_name 
            FROM appointments a 
            LEFT JOIN doctors d ON a.doctor_id = d.id
            LEFT JOIN patients p ON a.patient_id = p.id
            WHERE " . ('$role' === 'doctor' ? "d.user_id = ?" : "p.user_id = ?")
        );
        \$stmt->execute([\$userId]);
        \$appointments = \$stmt->fetchAll();
        break;
        
    case 'patients':
        \$stmt = \$pdo->prepare("
            SELECT DISTINCT p.* FROM patients p
            JOIN appointments a ON p.id = a.patient_id
            JOIN doctors d ON a.doctor_id = d.id
            WHERE d.user_id = ?
        ");
        \$stmt->execute([\$userId]);
        \$patients = \$stmt->fetchAll();
        break;
        
    case 'chat':
        \$stmt = \$pdo->prepare("
            SELECT DISTINCT u.* FROM users u
            JOIN chat_messages cm ON (cm.sender_id = u.id OR cm.receiver_id = u.id)
            WHERE (cm.sender_id = ? OR cm.receiver_id = ?)
            AND u.id != ?
        ");
        \$stmt->execute([\$userId, \$userId, \$userId]);
        \$contacts = \$stmt->fetchAll();
        break;
}
?>

<div class="container mx-auto px-4 py-8">
    <h2 class="text-2xl font-bold mb-6"><?php echo htmlspecialchars(\$title); ?></h2>
    <div class="bg-white rounded-lg shadow-md p-6">
        <?php include "../templates/$role/$page.php"; ?>
    </div>
</div>

<script src="../assets/js/<?php echo $page; ?>.js"></script>
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
