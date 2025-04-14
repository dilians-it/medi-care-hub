<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="card">
        <h3 class="text-xl font-bold mb-4">Book New Appointment</h3>
        <form id="appointmentForm" class="space-y-4">
            <div class="form-group">
                <label class="form-label">Select Doctor</label>
                <select name="doctor_id" required class="form-input">
                    <?php
                    $stmt = $pdo->query("SELECT d.*, u.username FROM doctors d JOIN users u ON d.user_id = u.id");
                    while ($doctor = $stmt->fetch()) {
                        echo "<option value='{$doctor['id']}'>{$doctor['username']} ({$doctor['specialization']})</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Appointment Date</label>
                <input type="datetime-local" name="appointment_date" required class="form-input">
            </div>
            <div class="form-group">
                <label class="form-label">Reason</label>
                <textarea name="reason" required class="form-input"></textarea>
            </div>
            <button type="submit" class="btn-primary">Book Appointment</button>
        </form>
    </div>
    
    <div class="card">
        <h3 class="text-xl font-bold mb-4">My Appointments</h3>
        <div class="space-y-4">
            <?php foreach ($appointments as $apt): ?>
                <div class="p-4 border rounded">
                    <div class="flex justify-between">
                        <h4 class="font-bold">Dr. <?php echo htmlspecialchars($apt['doctor_name']); ?></h4>
                        <span class="badge <?php echo $apt['status']; ?>"><?php echo ucfirst($apt['status']); ?></span>
                    </div>
                    <p class="text-gray-600"><?php echo htmlspecialchars($apt['reason']); ?></p>
                    <p class="text-sm text-gray-500"><?php echo formatDateTime($apt['appointment_date']); ?></p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
