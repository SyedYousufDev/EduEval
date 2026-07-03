# 📊 EduEval - Faculty Performance & Feedback Portal

> A complete web-based faculty evaluation system built with Core PHP and MySQL

---

## 🎯 Project Overview

EduEval is a web-based **Faculty Performance and Feedback Portal** that automates the faculty evaluation process. Students evaluate faculty members on multiple criteria while administrators view real-time analytics and performance metrics.

### Key Features
- 🔐 **Secure Authentication** - Student & Admin login with session management
- ⭐ **Multi-Criteria Ratings** - Rate faculty on Quality, Punctuality, and Engagement (1-5 stars)
- 🚫 **Duplicate Prevention** - One evaluation per student per faculty member
- 📊 **Real-Time Analytics** - Live performance metrics with SQL aggregates
- 💬 **Sentiment Analysis** - Automatic sentiment detection from comments
- 👨‍🏫 **Faculty Management** - CRUD operations for faculty and departments
- 📱 **Responsive Design** - Works on all devices

---

## 🛠️ Technology Stack

| Layer | Technology |
|-------|------------|
| **Backend** | PHP 7.4+, MySQL, PDO |
| **Frontend** | HTML5, CSS3, JavaScript |
| **Server** | Apache (XAMPP/WAMP) |
| **Security** | Password Hashing, Prepared Statements, Session Management |

---

## 📂 Project Structure

```
EduEval/
├── config/
│   ├── database.php
│   └── session.php
├── includes/
│   ├── header.php
│   ├── footer.php
│   └── functions.php
├── student/
│   ├── register.php
│   ├── login.php
│   ├── dashboard.php
│   ├── evaluate.php
│   ├── success.php
│   └── logout.php
├── admin/
│   ├── login.php
│   ├── dashboard.php
│   ├── faculty_manage.php
│   ├── department_manage.php
│   ├── view_evaluations.php
│   └── logout.php
├── assets/
│   ├── css/style.css
│   └── js/script.js
├── sql/
│   └── edueval.sql
└── index.php
```

---

## 🗄️ Database Schema

```sql
-- 5 Tables: admin, departments, faculty, students, evaluations
-- Key relationships:
-- faculty.department_id → departments.id
-- students.department_id → departments.id
-- evaluations.student_id → students.id
-- evaluations.faculty_id → faculty.id
-- UNIQUE KEY (student_id, faculty_id) prevents duplicate submissions
```

---

## 🚀 Installation Guide

### Prerequisites
- XAMPP/WAMP/MAMP installed
- PHP 7.4+
- MySQL 5.7+

### Steps

1. **Clone or Download**
```bash
git clone https://github.com/yourusername/EduEval.git
```

2. **Move to htdocs**
```bash
C:\xampp\htdocs\EduEval
```

3. **Start XAMPP Services**
- Start Apache
- Start MySQL

4. **Import Database**
- Open phpMyAdmin: `http://localhost/phpmyadmin`
- Create database: `edueval_db`
- Import `sql/edueval.sql`

5. **Update Database Credentials**
```php
// config/database.php
$host = 'localhost';
$dbname = 'edueval_db';
$username = 'root';
$password = '';
```

6. **Access Application**
```bash
http://localhost/EduEval
```

---

## 🔑 Default Credentials

| Role | Username/Email | Password |
|------|----------------|----------|
| **Admin** | `admin` | `password` |
| **Student** | `student@test.com` | `student123` |

---

## 🔒 Security Features

| Feature | Implementation |
|---------|----------------|
| SQL Injection | PDO Prepared Statements |
| XSS Protection | `htmlspecialchars()` |
| Password Security | `password_hash()` / `password_verify()` |
| Session Security | PHP Sessions with validation |
| Auth Guards | `redirectIfNotStudent()` / `redirectIfNotAdmin()` |

---

## 🔮 Future Improvements

- [ ] Email notifications for evaluation reminders
- [ ] Department head role (view only their department)
- [ ] PDF/Excel export of reports
- [ ] Anonymous feedback option
- [ ] Historical trend analysis across semesters

---

## 👥 Team

| Name | Role |
|------|------|
| **Syed Muhammad Yousuf** | Developer |
| **Mehran Ullah** | Developer |

**Submitted To:** Sir Engr Muhammad Humayun  
**Semester:** 6th Semester  
**Department:** Software Engineering

---

## 📄 License

This project is for educational purposes only.

---

**⭐ If you found this project helpful, please give it a star!**