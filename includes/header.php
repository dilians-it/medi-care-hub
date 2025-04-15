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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="../assets/css/style.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <nav class="bg-white border-b border-gray-100 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="/medi-care-hub" class="flex items-center space-x-3">
                        <span class="material-icons text-blue-600 text-3xl">medical_services</span>
                        <span class="text-blue-600 text-xl font-bold tracking-tight">MediCare Hub</span>
                    </a>
                </div>

                <div class="flex items-center space-x-6">
                    <div class="relative" id="notificationDropdown">
                        <button class="p-2 hover:bg-gray-100 rounded-full relative transition-colors duration-200">
                            <span class="notification-count absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center animate-pulse"></span>
                            <span class="material-icons text-gray-600">notifications</span>
                        </button>
                        <div class="hidden absolute right-0 mt-2 w-96 bg-white rounded-lg shadow-xl text-gray-700 z-50 border border-gray-100">
                            <div class="p-4 max-h-[70vh] overflow-y-auto" id="notificationList"></div>
                        </div>
                    </div>

                    <div class="flex items-center space-x-4">
                        <div class="hidden md:flex items-center space-x-2">
                            <span class="material-icons text-gray-400">account_circle</span>
                            <span class="text-gray-700 font-medium"><?php echo ucfirst($_SESSION['role']); ?></span>
                        </div>
                        <div class="h-6 w-px bg-gray-200"></div>
                        <a href="/medi-care-hub/auth/logout.php" 
                           class="flex items-center space-x-1 text-gray-600 hover:text-red-600 transition-colors duration-200">
                            <span class="material-icons text-sm">logout</span>
                            <span>Logout</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="bg-gray-50 border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex overflow-x-auto space-x-1 py-3 scrollbar-hide">
                <?php if ($role === 'admin'): ?>
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-icons">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="hospitals.php" class="nav-link">
                        <span class="material-icons">local_hospital</span>
                        <span>Hospitals</span>
                    </a>
                    <a href="doctors.php" class="nav-link">
                        <span class="material-icons">person</span>
                        <span>Doctors</span>
                    </a>
                    <a href="patients.php" class="nav-link">
                        <span class="material-icons">groups</span>
                        <span>Patients</span>
                    </a>
                    <a href="../chat.php" class="nav-link">
                        <span class="material-icons">chat</span>
                        <span>Messages</span>
                    </a>
                    <a href="reports.php" class="nav-link">
                        <span class="material-icons">assessment</span>
                        <span>Reports</span>
                    </a>
                <?php elseif ($role === 'hospital'): ?>
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-icons">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="doctors.php" class="nav-link">
                        <span class="material-icons">person</span>
                        <span>Doctors</span>
                    </a>
                    <a href="appointments.php" class="nav-link">
                        <span class="material-icons">event</span>
                        <span>Appointments</span>
                    </a>
                    <a href="feed.php" class="nav-link">
                        <span class="material-icons">rss_feed</span>
                        <span>Feed</span>
                    </a>
                    <a href="../chat.php" class="nav-link">
                        <span class="material-icons">chat</span>
                        <span>Messages</span>
                    </a>
                <?php elseif ($role === 'doctor'): ?>
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-icons">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="appointments.php" class="nav-link">
                        <span class="material-icons">event</span>
                        <span>Appointments</span>
                    </a>
                    <a href="patients.php" class="nav-link">
                        <span class="material-icons">groups</span>
                        <span>Patients</span>
                    </a>
                    <a href="reports.php" class="nav-link">
                        <span class="material-icons">assessment</span>
                        <span>Reports</span>
                    </a>
                    <a href="../chat.php" class="nav-link">
                        <span class="material-icons">chat</span>
                        <span>Messages</span>
                    </a>
                <?php elseif ($role === 'patient'): ?>
                    <a href="dashboard.php" class="nav-link">
                        <span class="material-icons">dashboard</span>
                        <span>Dashboard</span>
                    </a>
                    <a href="appointments.php" class="nav-link">
                        <span class="material-icons">event</span>
                        <span>Appointments</span>
                    </a>
                    <a href="reports.php" class="nav-link">
                        <span class="material-icons">assessment</span>
                        <span>Reports</span>
                    </a>
                    <a href="feed.php" class="nav-link">
                        <span class="material-icons">rss_feed</span>
                        <span>Feed</span>
                    </a>
                    <a href="../chat.php" class="nav-link">
                        <span class="material-icons">chat</span>
                        <span>Messages</span>
                    </a>
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
