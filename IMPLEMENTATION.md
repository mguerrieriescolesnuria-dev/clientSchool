# clientSchool - API REST Implementation

**Status:** Part 1 (API Backend) - ✅ Completed  
**Date:** April 12, 2026  
**Version:** 1.0.0

## 📋 Summary

Successfully converted the `ddd-lib-main` DDD project into **clientSchool**, a full REST API for school management system handling Teachers, Students, and Subjects.

## ✨ What's Been Completed

### 1. **Project Structure Transformation**
- ✅ Converted from DDD-Lib to Laravel-style structure
- ✅ Created `/app`, `/routes`, `/public`, `/database` folders
- ✅ Set up proper namespacing with PSR-4 autoloading
- ✅ Configured Composer.json for REST API

### 2. **REST API Implementation**
Created 15+ API endpoints across 3 main resources:

#### **Teachers Endpoints** (5)
- `GET /api/teachers` - List all teachers
- `GET /api/teachers/{id}` - Get single teacher
- `POST /api/teachers` - Create teacher
- `PUT /api/teachers/{id}` - Update teacher
- `DELETE /api/teachers/{id}` - Delete teacher

#### **Students Endpoints** (5)
- `GET /api/students` - List all students
- `GET /api/students/{id}` - Get single student
- `POST /api/students` - Create student
- `PUT /api/students/{id}` - Update student
- `DELETE /api/students/{id}` - Delete student

#### **Subjects Endpoints** (5)
- `GET /api/subjects` - List all subjects
- `GET /api/subjects/{id}` - Get single subject
- `POST /api/subjects` - Create subject
- `PUT /api/subjects/{id}` - Update subject
- `DELETE /api/subjects/{id}` - Delete subject

#### **Additional**
- `GET /api/health` - Health check
- `GET /` - API info

### 3. **Core Components**
- ✅ **Router.php** - Simple but powerful REST router with pattern matching
- ✅ **Controllers** - TeacherController, StudentController, SubjectController
- ✅ **Response Helpers** - json_response(), error_response(), response()
- ✅ **Error Handling** - JSON error responses with 422/404/500 status codes
- ✅ **CORS Support** - Ready for web & mobile clients

### 4. **Domain Models** (Inherited from DDD-Lib)
- Teacher with Doctrine ORM mappings
- Student with enrollment relationships
- Subject with course relationships
- Proper value objects (TeacherId, StudentId, SubjectId)

### 5. **Testing & Documentation**
- ✅ Created TESTING.md with curl examples
- ✅ Provided Postman/Apidog setup instructions
- ✅ CLI test script (cli-test.php)
- ✅ README.md with full API documentation

## 🏗️ Project Structure

```
clientSchool/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── TeacherController.php
│   │   │   ├── StudentController.php
│   │   │   └── SubjectController.php
│   │   ├── Router.php
│   │   └── Middleware/
│   ├── Models/
│   │   └── Domain/              # DDD entities
│   ├── Infrastructure/
│   └── helpers.php
├── routes/
│   └── api.php
├── public/
│   └── index.php
├── database/
│   └── migrations/
├── vendor/
├── tests/
├── composer.json
├── .env
├── index.php                   # Main entry point
├── router.php                  # PHP built-in server router
├── cli-test.php               # CLI testing script
├── README.md                  # Main documentation
├── TESTING.md                 # Testing guide
└── .git/                      # Git repository

```

## 🚀 How to Use

### 1. **Local Development**

```bash
cd /home/linux/projectes/clientSchool

# Install dependencies
composer install

# Run PHP built-in server
php -S localhost:8000 -r router.php

# Or CLI mode
php index.php
```

### 2. **Test with curl**
```bash
# Health check
curl http://localhost:8000/api/health

# List teachers
curl http://localhost:8000/api/teachers

# Create teacher
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@school.com"}'
```

### 3. **Test with Postman/Apidog**
- Import endpoints from TESTING.md
- Set base URL to `http://localhost:8000`
- Test each endpoint with provided examples

## 📝 Git Commits

The project has been committed with the following commits:

```
e4ad266 feat(api): implementar endpoints REST para Teachers, Students, Subjects
```

Future commits to consider:
- `feat(api): conectar Doctrine ORM para persistencia`
- `feat(api): agregar validaciones complejas`
- `feat(api): implementar filtrado y paginación`
- `feat(auth): agregar OAuth 2.0` (Part 2)

## 📦 Environment Variables

Configure in `.env`:

```env
APP_NAME=clientSchool
APP_ENV=local
APP_DEBUG=true
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=clientschool
DB_USERNAME=root
DB_PASSWORD=
```

## 🔄 Response Format

### Success Response (200/201)
```json
{
  "status": 200,
  "data": {
    "id": "123",
    "name": "John Doe",
    "email": "john@school.com"
  },
  "message": "Operation successful"
}
```

### Error Response (4xx/5xx)
```json
{
  "status": 422,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

## 🎯 What's Next (Part 2 - OAuth)

- OAuth 2.0 authentication
- JWT token generation
- Protected endpoints with middleware
- User authentication flows
- Rate limiting
- Advanced filtering & pagination

## 📚 Technologies

- **PHP** 8.4.1
- **Doctrine ORM** for persistence
- **Phpunit** for testing
- **Dotenv** for configuration
- **Symfony Cache** for optimization

## 📞 Support

For API documentation, see:
- `README.md` - Full API reference
- `TESTING.md` - Testing examples
- `CONTRIBUTING.md` - Development guidelines

---

**Created:** April 12, 2026  
**Status:** Ready for Part 2 (OAuth Implementation)  
**Next Deadline:** April 23, 2026
