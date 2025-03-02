# EduWeb Backend Project Documentation

## 🌟 Project Overview
EduWeb is a comprehensive educational platform backend built with PHP, featuring role-based access control, course management, and payment integration.

## 🔧 Technical Stack
- **Backend**: PHP
- **Database**: MySQL/PDO
- **Authentication**: JWT (JSON Web Tokens)
- **File Storage**: Local storage for videos and images
- **Payment**: Stripe integration
- **Environment**: XAMPP

## 🔐 Core Features

### 1. Authentication & Authorization
- User registration and login ✔️
- Admin-specific login ✔️
- JWT-based authentication ✔️
- Password reset functionality ✔️
- Role-based access (Admin, Teacher, Student) ✔️

### 2. User Management
- Profile management ✔️
- Profile picture upload ✔️
- Last activity tracking ✔️
- User listings by role ✔️
  - All users list
  - Student list
  - Teacher list
  - Recent users dashboard

### 3. Course Management
- Course CRUD operations ✔️
- Video content upload ✔️
- Course categorization ✔️
- Course enrollment system ✔️
- Search functionality by title ✔️

### 4. Teacher Features
- Course publishing ✔️
- Course management dashboard ✔️
- Course editing and deletion ✔️
- Video content management ✔️

### 5. Student Features
- Course enrollment ✔️
- Course viewing ✔️
- Enrolled courses list ✔️
- Course search ✔️

### 6. Admin Features
- User management ✔️
- Course oversight ✔️
- Statistics dashboard ✔️
- Content moderation ✔️

### 7. Payment Integration
- Stripe payment integration 🔄

## 📁 Project Structure
```
eduwebbackend/
├── src/
│   ├── routes/
│   ├── controllers/
│   ├── config/
│   └── models/
├── vendor/
├── .env
└── index.php
```

## 🔄 API Endpoints

### Authentication
```
POST /login.php
POST /admin-login.php
POST /registration.php
POST /reset-password-request.php
POST /confirm-reset-password.php
```

### Course Management
```
POST /publish-course.php
GET  /mycourse-list.php
GET  /user-showed-course-list.php
POST /enroll-course.php
GET  /user-enrolled-course.php
GET  /search-course-by-title.php
```

### User Management
```
GET  /userlist.php
GET  /studentlist.php
GET  /teacherlist.php
POST /update-profile.php
GET  /user-info.php
```

## 🎯 Future Enhancements
1. Real-time messaging system ❗
2. Discussion forums ❗
3. Email notifications ❗
4. Progress tracking system ❗

## ⚙️ Environment Setup
1. Install XAMPP
2. Clone repository to htdocs
3. Configure .env file
4. Set up database
5. Install dependencies:
```bash
composer install
```

## 🔒 Security Features
- JWT Authentication
- Password hashing
- Input sanitization
- Role-based access control
- Secure file upload validation