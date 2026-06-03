# Google Classroom Clone

A feature-rich Laravel application mimicking Google Classroom with role-based access (Admin, Teacher, Student), assignment management, grading system, and audit logging.

## Features

### 🔐 **Authentication & Authorization**
- Role-based access control (Admin, Teacher, Student)
- User registration and login
- Email verification
- Secure password management

### 👨‍💼 **Admin Dashboard**
- Manage all users (create, edit, delete)
- Assign/change user roles
- View complete activity log with filtering and export
- Track all system actions with audit trail

### 👨‍🏫 **Teacher Features**
- Create and manage multiple classes
- Auto-generate class codes for student invitations
- Generate QR codes for easy class joining
- Create assignments with due dates and file attachments
- Grade student submissions
- Add comments on submissions
- View class announcements
- Manage enrolled students

### 👨‍🎓 **Student Features**
- Join classes using class code or QR code
- Upload profile picture
- View assigned assignments
- Submit assignments with file uploads (PDF, DOCX, PPTX, JPG, PNG, ZIP)
- Comment on assignments
- Track submission status and grades
- View teacher feedback

### ✨ **Extra Features**
- Dark mode / Light mode toggle
- Responsive design (mobile, tablet, desktop)
- Activity audit logging with IP tracking
- Export audit logs as CSV
- File attachment support

## Tech Stack

- **Laravel 13.8**: PHP Framework
- **Tailwind CSS**: UI Styling with dark mode support
- **Vite**: Build tool and dev server
- **Pest**: Testing framework
- **Endroid QR Code**: QR code generation
- **Database**: SQLite (default)

## Installation

### Prerequisites
- PHP 8.3+
- Composer
- Node.js 18+
- npm or yarn

### Setup Steps

1. **Clone the repository**
```bash
cd c:\laragon\www\class
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Run database migrations**
```bash
php artisan migrate
```

6. **Build assets**
```bash
npm run build
```

7. **Start development server**
```bash
# Terminal 1: PHP Server
php artisan serve

# Terminal 2: Vite Dev Server
npm run dev
```

## Database Structure

### Users Table
- Stores user information with roles (admin, teacher, student)
- Profile picture and bio
- Last login tracking

### Classes Table
- Created by teachers
- Auto-generated class codes
- QR code paths
- Announcements

### Assignments Table
- Created by teachers for classes
- Due dates and point values
- Descriptions and attachments

### Submissions Table
- Student submissions per assignment
- Grades and submission status
- Timestamps for submission and grading

### Submission Comments Table
- Comments from teachers on submissions
- Comments from students

### Audit Logs Table
- Tracks all user actions
- IP address and user agent logging
- Entity tracking for changes

## User Roles

### Admin (🔴)
- Access: `/admin/users` - Manage all users
- Access: `/admin/audit-logs` - View activity logs
- Permissions: Create, edit, delete users; view system-wide audit trail

### Teacher (🟢)
- Access: `/teacher/classes` - Create and manage classes
- Access: View/grade student submissions
- Permissions: Create classes, invite students, create assignments, grade work

### Student (🔵)
- Access: `/student/classes` - Join and view enrolled classes
- Access: Submit assignments and view grades
- Permissions: Join classes, submit work, view feedback

## Key Routes

### Public Routes
- `GET /` - Welcome page
- `GET /register` - User registration
- `GET /login` - User login
- `GET /forgot-password` - Password reset

### Authenticated Routes
- `GET /dashboard` - Main dashboard
- `GET /profile` - Profile management

### Admin Routes
- `GET /admin/users` - User management
- `GET /admin/audit-logs` - Audit trail viewer
- `GET /admin/audit-logs/export` - Export audit logs

### Teacher Routes
- `GET /teacher/classes` - Class list
- `GET /teacher/classes/create` - Create class
- `GET /teacher/classes/{class}` - Class details
- `GET /teacher/assignments` - Assignment management
- `GET /teacher/submissions/{submission}` - Grade submission

### Student Routes
- `GET /student/classes` - Enrolled classes
- `POST /student/classes/join` - Join class by code
- `GET /student/assignments/{assignment}/submit` - Submit assignment

## Dark Mode

Toggle between light and dark mode using the theme button in the navbar. Preference is saved to localStorage.

## File Upload Limits

- Profile pictures: 2MB (JPG, PNG)
- Assignment files: 10MB each (any file type)
- Submission files: 10MB each (PDF, DOCX, PPTX, JPG, PNG, ZIP)

## Audit Logging

All significant actions are logged:
- User management (create, update, delete)
- Class creation and management
- Assignment creation and grading
- Submissions and comments
- IP address and user agent tracking

Export logs as CSV for reporting.

## Testing

Run tests using Pest:
```bash
php artisan test
```

## Troubleshooting

### Database Issues
```bash
# Fresh migration (careful - clears all data)
php artisan migrate:fresh

# Rollback to previous state
php artisan migrate:rollback
```

### Asset Issues
```bash
# Rebuild assets
npm run build

# For development with hot reload
npm run dev
```

### Permission Issues
```bash
php artisan storage:link
chmod -R 775 storage/ bootstrap/cache/
```

## Future Enhancements

- [ ] Bulk file upload for assignments
- [ ] Email notifications
- [ ] Assignment plagiarism detection
- [ ] Student group projects
- [ ] Gradebook with statistics
- [ ] Assignment rubrics
- [ ] Late submission penalties
- [ ] Real-time notifications
- [ ] API for mobile app
- [ ] Assignment scheduling

## Security Features

- CSRF protection on all POST/PUT/DELETE routes
- SQL injection prevention (Eloquent ORM)
- Password hashing with bcrypt
- Role-based middleware
- Audit logging of all actions
- File upload validation

## License

MIT License - See LICENSE file for details

## Support

For issues or questions, please refer to the Laravel documentation:
- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS Documentation](https://tailwindcss.com/docs)

---

**Created**: June 2024
**Version**: 1.0.0
