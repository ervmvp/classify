# 🚀 Google Classroom Clone - Quick Start Guide

## ✅ Project Setup Complete!

Your Google Classroom-like application has been successfully created with all requested features. Here's how to get started:

## 📋 Quick Setup (5 Minutes)

### Step 1: Navigate to Project
```bash
cd c:\laragon\www\class
```

### Step 2: Install Dependencies
```bash
composer install
npm install
```

### Step 3: Build Assets
```bash
npm run build
```

### Step 4: Start Development

**Terminal 1 - PHP Server:**
```bash
php artisan serve
```

**Terminal 2 - Vite Dev Server:**
```bash
npm run dev
```

Then visit: http://localhost:8000

## 👥 Test Accounts

Create test users via the admin panel or use:
```bash
php artisan tinker
```

Then create users:
```php
User::create(['name' => 'Admin', 'email' => 'admin@test.com', 'password' => Hash::make('password'), 'role' => 'admin']);
User::create(['name' => 'Teacher', 'email' => 'teacher@test.com', 'password' => Hash::make('password'), 'role' => 'teacher']);
User::create(['name' => 'Student', 'email' => 'student@test.com', 'password' => Hash::make('password'), 'role' => 'student']);
```

## 🎯 Features Overview

### 👨‍💼 Admin Role
- **URL:** http://localhost:8000/admin/users
- Manage all users (create, edit, delete)
- Assign roles
- View activity audit trail

### 👨‍🏫 Teacher Role
- **URL:** http://localhost:8000/teacher/classes
- Create classes with auto-generated codes
- Generate QR codes for students
- Create assignments with attachments
- Grade submissions
- Manage enrolled students

### 👨‍🎓 Student Role
- **URL:** http://localhost:8000/student/classes
- Join classes with code or QR
- Submit assignments
- Upload files
- View grades
- Communicate with teachers

## 🎨 Dark Mode
Toggle dark/light mode using the moon/sun icon in the top navbar. Your preference is saved automatically!

## 📁 Important Files

- **Routes:** `routes/web.php` - All application routes
- **Controllers:** `app/Http/Controllers/` - Logic for each feature
- **Models:** `app/Models/` - Database relationships
- **Views:** `resources/views/` - UI templates
- **Migrations:** `database/migrations/` - Database schema

## 🔒 Default Admin Login
Register a new account and change the role to 'admin' via database:

```bash
php artisan tinker
User::first()->update(['role' => 'admin']);
```

## 📦 Database

The application uses MySQL by default. Ensure your `.env` file is configured:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

Run migrations if needed:
```bash
php artisan migrate --fresh
```

## 🛠 Useful Commands

```bash
# View all routes
php artisan route:list

# Fresh database (warning: deletes all data)
php artisan migrate:fresh

# Create new migration
php artisan make:migration table_name

# Run tests
php artisan test

# Clear cache
php artisan cache:clear

# Launch interactive shell
php artisan tinker
```

## 📝 Key Paths

| Role | Dashboard | URL |
|------|-----------|-----|
| Admin | Users & Audit Logs | `/admin/users` |
| Teacher | Classes | `/teacher/classes` |
| Student | Classes | `/student/classes` |
| All | Profile | `/profile` |

## 📤 File Upload Support

- **Profile Pictures:** JPG, PNG (max 2MB)
- **Assignment Files:** Any file (max 10MB)
- **Submission Files:** PDF, DOCX, PPTX, JPG, PNG, ZIP (max 10MB)

## 🚨 Troubleshooting

**Issue: Port 8000 already in use**
```bash
php artisan serve --port=8001
```

**Issue: Assets not showing**
```bash
npm run build
# or for development
npm run dev
```

**Issue: Database migration errors**
```bash
# Check if migrations ran
php artisan migrate:status

# Reset and run again
php artisan migrate:fresh --force
```

**Issue: Storage permissions**
```bash
php artisan storage:link
chmod -R 775 storage/ bootstrap/cache/
```

## 📚 Additional Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Dark Mode](https://tailwindcss.com/docs/dark-mode)
- [Vite Documentation](https://vitejs.dev/)

## 🎓 Next Steps

1. Register an account
2. Change role to 'admin' (via database)
3. Create test users
4. Create a class as a teacher
5. Enroll as a student
6. Create assignments and test submissions

---

**Version:** 1.0.0  
**Created:** June 2024  
**Framework:** Laravel 13.8  
**UI Framework:** Tailwind CSS  

Enjoy your classroom application! 🎉
