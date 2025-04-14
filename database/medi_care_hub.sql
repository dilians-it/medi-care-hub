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
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Hospitals table
CREATE TABLE hospitals (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    name VARCHAR(100) NOT NULL,
    address TEXT,
    phone VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Doctors table
CREATE TABLE doctors (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    hospital_id INT,
    specialization VARCHAR(100),
    experience_years INT,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- Patients table
CREATE TABLE patients (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    date_of_birth DATE,
    blood_group VARCHAR(5),
    FOREIGN KEY (user_id) REFERENCES users(id)
);

-- Appointments table
CREATE TABLE appointments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    doctor_id INT,
    patient_id INT,
    appointment_date DATETIME,
    status ENUM('pending', 'confirmed', 'completed', 'cancelled', 'rescheduled'),
    FOREIGN KEY (doctor_id) REFERENCES doctors(id),
    FOREIGN KEY (patient_id) REFERENCES patients(id)
);

-- Visit reports table
CREATE TABLE visit_reports (
    id INT PRIMARY KEY AUTO_INCREMENT,
    appointment_id INT,
    symptoms TEXT,
    diagnosis TEXT,
    prescription TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
);

-- Report attachments table
CREATE TABLE report_attachments (
    id INT PRIMARY KEY AUTO_INCREMENT,
    report_id INT,
    file_path VARCHAR(255),
    file_type ENUM('image', 'pdf', 'document'),
    FOREIGN KEY (report_id) REFERENCES visit_reports(id)
);

-- Hospital feed table
CREATE TABLE feed_posts (
    id INT PRIMARY KEY AUTO_INCREMENT,
    hospital_id INT,
    title VARCHAR(255),
    description TEXT,
    image_path VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (hospital_id) REFERENCES hospitals(id)
);

-- Chat messages table
CREATE TABLE chat_messages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    sender_id INT,
    receiver_id INT,
    message TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (sender_id) REFERENCES users(id),
    FOREIGN KEY (receiver_id) REFERENCES users(id)
);

-- Chatbot templates table
CREATE TABLE chatbot_templates (
    id INT PRIMARY KEY AUTO_INCREMENT,
    question VARCHAR(255),
    answer TEXT,
    created_by INT,
    FOREIGN KEY (created_by) REFERENCES users(id)
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
