# clientSchool - API REST Implementation

**Status:** Part 1 (API Backend) - ✅ Completed  
**Date:** April 12, 2026  
**Version:** 1.0.0

## 📋 Summary

Successfully created **clientSchool**, a full REST API for school management system handling Teachers, Students, and Subjects, following DDD (Domain-Driven Design) principles with a clean, organized structure.

## ✨ What's Been Completed

### 1. **Project Structure - DDD Pattern**
- ✅ Organized following Antonio's architecture (`/src/` structure)
- ✅ **Domain Layer**: Teacher, Student, Subject, Course, Enrollment, User
- ✅ **Application Layer**: Use Cases for business logic
- ✅ **Infrastructure Layer**: Repositories & REST Controllers
- ✅ Proper PSR-4 namespacing (`App\*`)

### 2. **REST API Implementation**
Created 15+ API endpoints across 3 main resources:

#### **Teachers Endpoints** (5)
```bash
GET    /api/teachers         → List all teachers
GET    /api/teachers/{id}    → Get single teacher
POST   /api/teachers         → Create teacher
PUT    /api/teachers/{id}    → Update teacher
DELETE /api/teachers/{id}    → Delete teacher
```

#### **Students Endpoints** (5)
```bash
GET    /api/students         → List all students
GET    /api/students/{id}    → Get single student
POST   /api/students         → Create student
PUT    /api/students/{id}    → Update student
DELETE /api/students/{id}    → Delete student
```

#### **Subjects Endpoints** (5)
```bash
GET    /api/subjects         → List all subjects
GET    /api/subjects/{id}    → Get single subject
POST   /api/subjects         → Create subject
PUT    /api/subjects/{id}    → Update subject
DELETE /api/subjects/{id}    → Delete subject
```

#### **Additional**
- `GET /api/health` - Health check
- `GET /` - API info

### 3. **Core Components**
- ✅ **Router.php** - Routing engine with pattern matching (`/api/teachers/{id}` format)
- ✅ **Controllers** - TeacherController, StudentController, SubjectController
- ✅ **Response Helpers** - `json_response()`, `error_response()`, `response()`
- ✅ **Error Handling** - Proper HTTP status codes (200, 201, 400, 404, 422, 500)
- ✅ **CORS Support** - Ready for web & mobile clients
- ✅ **Helpers** - Utility functions in `src/helpers.php`

### 4. **Domain Models** (DDD Entities)
- Teacher with subjects relationship
- Student with enrollments relationship
- Subject with course relationship
- Course for course management
- Enrollment for student-subject relationships
- User for authentication (future)

### 5. **Testing & Documentation**
- ✅ **TESTING.md** - 30+ curl examples
- ✅ **README.md** - Full API documentation
- ✅ **IMPLEMENTATION.md** - This file
- ✅ **GITHUB_SETUP.md** - GitHub deployment guide
- ✅ **cli-test.php** - CLI testing script

## 📁 Project Structure

```
clientSchool/
├── src/
│   ├── Domain/              # DDD Entities - School domain
│   ├── Application/         # Use Cases - Business logic
│   ├── Infrastructure/
│   │   ├── Persistence/     # Database repositories
│   │   └── Web/             # REST Controllers + Router
│   └── helpers.php
├── routes/
├── database/
├── tests/
├── config/
├── public/
├── index.php                # Main API entry point
├── router.php               # PHP server router
├── cli-test.php            # CLI tests
└── composer.json
```

## 🚀 How to Use

### 1. Installation & Setup

```bash
cd /home/linux/projectes/clientSchool

# Install dependencies
composer install

# Update autoloader
composer dumpautoload
```

### 2. Test with PHP Built-in Server

```bash
# Start server on port 8000
php -S localhost:8000 -r router.php

# In another terminal, test health endpoint
curl http://localhost:8000/api/health
```

### 3. Test with Direct CLI

```bash
# No server needed - execute directly
REQUEST_METHOD=GET REQUEST_URI=/api/health php index.php
REQUEST_METHOD=GET REQUEST_URI=/api/teachers php index.php
```

### 4. Test with curl

```bash
# List teachers
curl http://localhost:8000/api/teachers

# Get single teacher
curl http://localhost:8000/api/teachers/1

# Create teacher
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{"name":"John","email":"john@school.com"}'
```

### 5. Test with Postman/Apidog

- Import endpoints from TESTING.md
- Set base URL to `http://localhost:8000`
- Test each endpoint with provided examples

## 📝 Response Format

### Success Response (200/201)
```json
{
  "status": 200,
  "data": {
    "id": "123",
    "name": "John Doe",
    "email": "john@school.com"
  }
}
```

### Error Response (422/404/500)
```json
{
  "status": 422,
  "message": "Validation failed",
  "errors": {
    "email": ["Email is required"]
  }
}
```

## 🔄 Git Commits

```
7b7a556 refactor: reorganizar estructura a src/, eliminar duplicados y entidades de libros
1aa0e01 docs: agregar guía de configuración GitHub
33a1f0f docs: agregar documentación de implementación
e4ad266 feat(api): implementar endpoints REST para Teachers, Students, Subjects
```

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

## 🎯 What's Next (Part 2 - OAuth)

- OAuth 2.0 authentication
- JWT token generation
- Protected endpoints with middleware
- User authentication flows
- Rate limiting
- Advanced filtering & pagination

## 📚 Technologies

- **PHP** 8.1+
- **Doctrine ORM** for persistence
- **PHPUnit** for testing
- **Dotenv** for configuration
- **Symfony Cache** for optimization

## ✅ Evaluation Checklist (Parte 1)

- ✅ GitHub repository created
- ✅ All 17+ endpoints implemented
- ✅ Meaningful git commits
- ✅ Documentation with test examples
- ✅ Clean, functional code
- ✅ DDD architecture followed
- ✅ Proper error handling

---

**Created:** April 12, 2026  
**Status:** Ready for Presentation  
**Next Deadline:** April 23, 2026 (Part 2 - OAuth)
