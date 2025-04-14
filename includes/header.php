<?php
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}

$role = $_SESSION['role'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare Hub - <?php echo ucfirst($role); ?> Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100">
    <nav class="bg-blue-600 text-white p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-xl font-bold">MediCare Hub</h1>
            <div class="flex items-center space-x-4">
                <div class="relative" id="notificationDropdown">
                    <button class="p-2 hover:bg-blue-700 rounded-full">
                        <span class="notification-count"></span>
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path d="M15 17h5l-1.4-1.4a6 6 0 0 1-1.6-4.1V8a6 6 0 0 0-6-6 6 6 0 0 0-6 6v3.5c0 1.5-.6 3-1.6 4.1L2 17h5m3 0v1a3 3 0 0 0 6 0v-1"></path>
                        </svg>
                    </button>
                    <div class="hidden absolute right-0 mt-2 w-80 bg-white rounded-lg shadow-xl text-gray-700">
                        <div class="p-4 max-h-96 overflow-y-auto" id="notificationList"></div>
                    </div>
                </div>
                <span><?php echo ucfirst($role); ?></span>
                <a href="<?php echo dirname($_SERVER['PHP_SELF'], 2); ?>/auth/logout.php" class="hover:text-gray-200">Logout</a>
            </div>
        </div>
    </nav>

    <!-- Role-specific navigation menu -->
    <div class="bg-white shadow-sm">
        <div class="container mx-auto">
            <div class="flex space-x-6 p-4">
                <?php if ($role === 'admin'): ?>
                    <a href="/medi-care-hub/admin/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="/medi-care-hub/admin/hospitals.php" class="nav-link">Hospitals</a>
                    <a href="/medi-care-hub/admin/doctors.php" class="nav-link">Doctors</a>
                    <a href="/medi-care-hub/admin/patients.php" class="nav-link">Patients</a>
                    <a href="/medi-care-hub/admin/reports.php" class="nav-link">Reports</a>
                <?php elseif ($role === 'hospital'): ?>
                    <a href="/medi-care-hub/hospital/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="/medi-care-hub/hospital/doctors.php" class="nav-link">Doctors</a>
                    <a href="/medi-care-hub/hospital/appointments.php" class="nav-link">Appointments</a>
                    <a href="/medi-care-hub/hospital/feed.php" class="nav-link">Feed</a>
                    <a href="/medi-care-hub/hospital/chat.php" class="nav-link">Messages</a>
                <?php elseif ($role === 'doctor'): ?>
                    <a href="/medi-care-hub/doctor/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="/medi-care-hub/doctor/appointments.php" class="nav-link">Appointments</a>
                    <a href="/medi-care-hub/doctor/patients.php" class="nav-link">Patients</a>
                    <a href="/medi-care-hub/doctor/reports.php" class="nav-link">Reports</a>
                    <a href="/medi-care-hub/doctor/chat.php" class="nav-link">Messages</a>
                <?php elseif ($role === 'patient'): ?>
                    <a href="/medi-care-hub/patient/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="/medi-care-hub/patient/appointments.php" class="nav-link">Appointments</a>
                    <a href="/medi-care-hub/patient/reports.php" class="nav-link">Reports</a>
                    <a href="/medi-care-hub/patient/feed.php" class="nav-link">Feed</a>
                    <a href="/medi-care-hub/patient/chat.php" class="nav-link">Messages</a>
                    <a href="/medi-care-hub/patient/chatbot.php" class="nav-link">AI Assistant</a>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Add notification handling script -->
    <script>
    $(document).ready(function() {
        function loadNotifications() {
            $.get('../api/notifications.php', function(data) {
                $('#notificationList').html(data.html);
                $('.notification-count').text(data.unread);
            });
        }

        $('#notificationDropdown button').click(function() {
            $('#notificationDropdown > div').toggleClass('hidden');
            $.post('../api/notifications.php', {action: 'mark_read'});
        });

        loadNotifications();
        setInterval(loadNotifications, 30000);
    });
    </script>
</body>
</html>
