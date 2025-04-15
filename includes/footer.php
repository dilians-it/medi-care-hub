<footer class="mt-auto bg-white border-t">
    <div class="max-w-7xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <div class="col-span-1 md:col-span-2">
                <div class="flex items-center space-x-3 mb-4">
                    <span class="material-icons text-blue-600 text-3xl">medical_services</span>
                    <span class="text-blue-600 text-xl font-bold">MediCare Hub</span>
                </div>
                <p class="text-gray-500 max-w-md">Connecting healthcare professionals with patients for better care and improved medical services.</p>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-4">Quick Links</h3>
                <ul class="space-y-3">
                    <li><a href="#" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">About Us</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Services</a></li>
                    <li><a href="#" class="text-gray-500 hover:text-blue-600 transition-colors duration-200">Contact</a></li>
                </ul>
            </div>
            
            <div>
                <h3 class="text-sm font-semibold text-gray-600 tracking-wider uppercase mb-4">Contact Us</h3>
                <ul class="space-y-3">
                    <li class="flex items-center space-x-2">
                        <span class="material-icons text-gray-400 text-sm">email</span>
                        <span class="text-gray-500">support@medicare-hub.com</span>
                    </li>
                    <li class="flex items-center space-x-2">
                        <span class="material-icons text-gray-400 text-sm">phone</span>
                        <span class="text-gray-500">+1 234 567 8900</span>
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="mt-8 pt-8 border-t border-gray-200">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <p class="text-gray-400 text-sm">&copy; <?php echo date('Y'); ?> MediCare Hub. All rights reserved.</p>
                <div class="flex space-x-6 mt-4 md:mt-0">
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">facebook</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">twitter</span>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-gray-500">
                        <span class="material-icons">linkedin</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</footer>

<script>
function showAlert(message, type = 'success') {
    const alert = document.createElement('div');
    alert.className = `fixed bottom-4 right-4 alert ${type === 'success' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'} z-50`;
    alert.innerHTML = `
        <span class="material-icons">${type === 'success' ? 'check_circle' : 'error'}</span>
        <span>${message}</span>
    `;
    document.body.appendChild(alert);
    setTimeout(() => alert.remove(), 3000);
}
</script>
</body>
</html>
