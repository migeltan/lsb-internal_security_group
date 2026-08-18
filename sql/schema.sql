-- ============================================================
-- SMART Internal Security Group Portal
-- Database Schema — Phase 1
-- Run this in phpMyAdmin (Import tab) or via MySQL CLI:
--   mysql -u root -p < schema.sql
-- ============================================================

CREATE DATABASE IF NOT EXISTS smart_portal
  CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE smart_portal;

-- ------------------------------------------------------------
-- users: admin/reviewer accounts (Phase 3)
-- ------------------------------------------------------------
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    full_name VARCHAR(150) NOT NULL,
    role ENUM('admin', 'reviewer') NOT NULL DEFAULT 'reviewer',
    is_active TINYINT(1) NOT NULL DEFAULT 1,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- applicants: shared personal info for BOTH application types
-- One applicant record per application (kept simple —
-- a returning applicant currently re-enters info; can be
-- normalized further later if needed)
-- ------------------------------------------------------------
CREATE TABLE applicants (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) NOT NULL UNIQUE, -- e.g. AP-2026-00001
    first_name VARCHAR(100) NOT NULL,
    middle_name VARCHAR(100),
    last_name VARCHAR(100) NOT NULL,
    suffix VARCHAR(20),
    date_of_birth DATE,
    place_of_birth VARCHAR(150),
    sex ENUM('Male', 'Female', 'Prefer not to say'),
    civil_status ENUM('Single', 'Married', 'Widowed', 'Separated', 'Other'),
    address TEXT,
    contact_number VARCHAR(30),
    email VARCHAR(150),
    applicant_type ENUM('Plantilla', 'Non-Plantilla', 'Consultant', 'Other') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- access_pass_applications: one row per access pass application
-- ------------------------------------------------------------
CREATE TABLE access_pass_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) NOT NULL UNIQUE,
    applicant_id INT NOT NULL,
    status ENUM('Draft','Submitted','Under Review','Incomplete/Returned','Approved','Rejected','Completed')
        NOT NULL DEFAULT 'Draft',
    date_submitted DATETIME NULL,
    date_reviewed DATETIME NULL,
    reviewed_by INT NULL,
    remarks TEXT,
    photo_path VARCHAR(255),
    declaration_name VARCHAR(150),
    declaration_date DATE,
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- family_background: repeatable entries per applicant
-- ------------------------------------------------------------
CREATE TABLE family_background (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    relationship ENUM('Father','Mother','Spouse','Child/Dependent') NOT NULL,
    name VARCHAR(150) NOT NULL,
    occupation VARCHAR(150),
    other_information VARCHAR(255),
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- educational_background: repeatable entries per applicant
-- ------------------------------------------------------------
CREATE TABLE educational_background (
    id INT AUTO_INCREMENT PRIMARY KEY,
    applicant_id INT NOT NULL,
    school VARCHAR(200) NOT NULL,
    degree VARCHAR(150),
    year_graduated VARCHAR(10),
    other_information VARCHAR(255),
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- vehicle_applications: one row per vehicle sticker application
-- ------------------------------------------------------------
CREATE TABLE vehicle_applications (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) NOT NULL UNIQUE,
    applicant_id INT NOT NULL,
    plate_number VARCHAR(20) NOT NULL,
    vehicle_type VARCHAR(50),
    make VARCHAR(80),
    model VARCHAR(80),
    color VARCHAR(40),
    year VARCHAR(10),
    registration_information VARCHAR(255),
    ownership ENUM('Registered to Applicant','Not Registered to Applicant') NOT NULL,
    status ENUM('Draft','Submitted','Under Review','Incomplete/Returned','Approved','Rejected','Completed')
        NOT NULL DEFAULT 'Draft',
    remarks TEXT,
    date_submitted DATETIME NULL,
    -- Clearance branch section (admin-only, section 13 of proposal)
    reviewed_by INT NULL,
    date_reviewed DATETIME NULL,
    clearance_status VARCHAR(50),
    approval_date DATE NULL,
    sticker_number VARCHAR(30),
    FOREIGN KEY (applicant_id) REFERENCES applicants(id) ON DELETE CASCADE,
    FOREIGN KEY (reviewed_by) REFERENCES users(id) ON DELETE SET NULL
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- documents: uploaded files for either application type
-- application_id here refers to the human-readable ref number
-- (AP-2026-00001 / VS-2026-00001), NOT a foreign key to a single
-- table, since it can point to either application type
-- ------------------------------------------------------------
CREATE TABLE documents (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) NOT NULL,
    document_type VARCHAR(100) NOT NULL, -- e.g. 'Letter Request', 'Valid ID 1', 'OR/CR'
    file_name VARCHAR(255) NOT NULL,
    file_path VARCHAR(255) NOT NULL,
    uploaded_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    verification_status ENUM('Pending','Verified','Rejected') NOT NULL DEFAULT 'Pending',
    INDEX idx_application_id (application_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- application_logs: simple audit trail
-- ------------------------------------------------------------
CREATE TABLE application_logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    application_id VARCHAR(20) NOT NULL,
    user_id INT NULL, -- NULL when the action was performed by the applicant, not an admin
    action VARCHAR(150) NOT NULL,
    remarks TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_application_id (application_id)
) ENGINE=InnoDB;

-- ------------------------------------------------------------
-- Seed one admin account for local testing.
-- Username: admin   Password: ChangeMe123!
-- CHANGE THIS before showing the prototype to anyone.
-- Hash below was generated with PHP's password_hash() (bcrypt).
-- ------------------------------------------------------------
INSERT INTO users (username, password_hash, full_name, role)
VALUES (
    'admin',
    '$2b$10$X/VsvureSAM61Fcng0kpfOfBuMS4hcrZ.J.I4fsNeJ9V80YtPTHDO',
    'Prototype Administrator',
    'admin'
);
