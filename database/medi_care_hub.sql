-- Create database
CREATE DATABASE IF NOT EXISTS medi_care_hub;
USE medi_care_hub;

-- Users table (common for all roles)
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    role ENUM('admin', 'hospital', 'doctor', 'patient') NOT NULL,
    profile_picture VARCHAR(255),
    status ENUM('active', 'inactive', 'suspended') DEFAULT 'active',
    last_login DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Hospitals table with enhanced fields
CREATE TABLE hospitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    email VARCHAR(100),
    website VARCHAR(255),
    description TEXT,
    facilities TEXT,
    working_hours TEXT,
    emergency_contact VARCHAR(20),
    latitude DECIMAL(10, 8),
    longitude DECIMAL(11, 8),
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Doctors table with enhanced fields
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hospital_id INT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    specialization VARCHAR(100),
    qualification TEXT,
    experience_years INT,
    consultation_fee DECIMAL(10, 2),
    available_days VARCHAR(100),
    time_slots TEXT,
    about TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE
);

-- Patients table with enhanced fields
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    first_name VARCHAR(50),
    last_name VARCHAR(50),
    date_of_birth DATE,
    gender ENUM('male', 'female', 'other'),
    blood_group VARCHAR(5),
    allergies TEXT,
    emergency_contact VARCHAR(20),
    address TEXT,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Enhanced appointments table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT,
    patient_id INT,
    appointment_date DATETIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'),
    reason TEXT,
    notes TEXT,
    reschedule_reason TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE,
    FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
);

-- Enhanced visit reports table
CREATE TABLE visit_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT,
    symptoms TEXT,
    diagnosis TEXT,
    prescription TEXT,
    treatment_plan TEXT,
    next_visit_date DATE,
    vital_signs JSON,
    lab_results TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id) ON DELETE CASCADE
);

-- Enhanced report attachments table
CREATE TABLE report_attachments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT,
    file_name VARCHAR(255),
    file_path VARCHAR(255),
    file_type ENUM('image', 'pdf', 'document'),
    file_size INT,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (report_id) REFERENCES visit_reports(id) ON DELETE CASCADE
);

-- Enhanced feed posts table
CREATE TABLE feed_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255),
    type ENUM('news', 'event', 'announcement'),
    start_date DATE,
    end_date DATE,
    status ENUM('draft', 'published', 'archived') DEFAULT 'published',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE
);

-- Enhanced chat messages table
CREATE TABLE chat_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    receiver_id INT,
    message TEXT,
    attachment_path VARCHAR(255),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

-- Enhanced chatbot templates
CREATE TABLE chatbot_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(255),
    answer TEXT,
    category VARCHAR(50),
    keywords TEXT,
    created_by INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES users(id)
);

-- New table for notifications
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255),
    message TEXT,
    type VARCHAR(50),
    is_read BOOLEAN DEFAULT FALSE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

-- New table for doctor schedules
CREATE TABLE doctor_schedules (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT,
    day_of_week ENUM('Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'),
    start_time TIME,
    end_time TIME,
    break_start TIME,
    break_end TIME,
    max_appointments INT,
    FOREIGN KEY (doctor_id) REFERENCES doctors(id) ON DELETE CASCADE
);

-- Insert sample data
INSERT INTO users (username, password, email, role) VALUES
('admin', 'admin123', 'admin@medicarehub.com', 'admin'),
('cityhospital', 'hospital123', 'city@hospital.com', 'hospital'),
('drmike', 'doctor123', 'mike@doctor.com', 'doctor'),
('drjane', 'doctor123', 'jane@doctor.com', 'doctor'),
('patient1', 'patient123', 'patient1@email.com', 'patient'),
('patient2', 'patient123', 'patient2@email.com', 'patient');

-- Insert hospitals
INSERT INTO hospitals (user_id, name, address, phone) VALUES
(2, 'City Hospital', '123 Main St, City', '555-0123');

-- Insert doctors
INSERT INTO doctors (user_id, hospital_id, specialization, experience_years) VALUES
(3, 1, 'Cardiology', 10),
(4, 1, 'Pediatrics', 8);

-- Insert patients
INSERT INTO patients (user_id, date_of_birth, blood_group) VALUES
(5, '1990-05-15', 'A+'),
(6, '1985-08-22', 'O-');

-- Insert appointments
INSERT INTO appointments (doctor_id, patient_id, appointment_date, status) VALUES
(1, 1, '2024-01-20 10:00:00', 'confirmed'),
(2, 2, '2024-01-21 14:30:00', 'pending');

-- Insert feed posts
INSERT INTO feed_posts (hospital_id, title, description) VALUES
(1, 'New Cardiology Department', 'We are excited to announce our new state-of-the-art cardiology department'),
(1, 'COVID-19 Vaccination Drive', 'Free vaccination drive this weekend');

-- Insert chatbot templates
INSERT INTO chatbot_templates (question, answer, created_by) VALUES
('How do I book an appointment?', 'You can book an appointment by logging in and selecting your preferred doctor and time slot.', 1),
('What are your visiting hours?', 'Our general visiting hours are from 9 AM to 8 PM daily.', 1);
