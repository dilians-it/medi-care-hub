    <footer class="bg-gray-800 text-white mt-8 py-4">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; <?php echo date('Y'); ?> MediCare Hub. All rights reserved.</p>
        </div>
    </footer>
    <script>
        // Common JavaScript functions
        function showAlert(message, type = 'success') {
            const alert = document.createElement('div');
            alert.className = `alert alert-${type}`;
            alert.textContent = message;
            document.body.insertBefore(alert, document.body.firstChild);
            setTimeout(() => alert.remove(), 3000);
        }
    </script>
</body>
</html>
