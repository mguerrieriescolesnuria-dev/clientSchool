# API Testing Guide - clientSchool

Complete testing guide for the clientSchool REST API.

## 🚀 Quick Start Testing

### Option 1: CLI Direct Testing (No Server Needed)

```bash
# Navigate to project
cd /home/linux/projectes/clientSchool

# Test health endpoint
REQUEST_METHOD=GET REQUEST_URI=/api/health php index.php

# Test list teachers
REQUEST_METHOD=GET REQUEST_URI=/api/teachers php index.php

# Test list students
REQUEST_METHOD=GET REQUEST_URI=/api/students php index.php

# Test list subjects
REQUEST_METHOD=GET REQUEST_URI=/api/subjects php index.php
```

### Option 2: PHP Built-in Server

```bash
# Start server
php -S localhost:8000 -r router.php

# Then use curl in another terminal (see examples below)
```

### Option 3: CLI Test Script

```bash
php cli-test.php
```

---

## 📋 API Testing Examples with curl

### Health Check

```bash
curl -X GET http://localhost:8000/api/health
```

**Expected Response:**
```json
{
  "status": 200,
  "data": {
    "status": "OK"
  }
}
```

---

## 👨‍🏫 Teachers Endpoints

### 1. List All Teachers
```bash
curl -X GET http://localhost:8000/api/teachers
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [
    {
      "id": "1",
      "name": "John Doe",
      "email": "john@school.com"
    },
    {
      "id": "2",
      "name": "Jane Smith",
      "email": "jane@school.com"
    }
  ]
}
```

### 2. Get Specific Teacher
```bash
curl -X GET http://localhost:8000/api/teachers/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "John Doe",
    "email": "john@school.com",
    "subjects": []
  }
}
```

### 3. Create New Teacher
```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john.doe@school.com"
  }'
```

**Expected Response (201 Created):**
```json
{
  "status": 201,
  "data": {
    "id": "507f1f77bcf86cd799439011",
    "name": "John Doe",
    "email": "john.doe@school.com"
  },
  "message": "Teacher created successfully"
}
```

### 4. Update Teacher
```bash
curl -X PUT http://localhost:8000/api/teachers/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane.doe@school.com"
  }'
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "Jane Doe",
    "email": "jane.doe@school.com"
  },
  "message": "Teacher updated successfully"
}
```

### 5. Delete Teacher
```bash
curl -X DELETE http://localhost:8000/api/teachers/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [],
  "message": "Teacher deleted successfully"
}
```

---

## 👨‍🎓 Students Endpoints

### 1. List All Students
```bash
curl -X GET http://localhost:8000/api/students
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [
    {
      "id": "1",
      "name": "Alice Johnson",
      "email": "alice@student.com"
    },
    {
      "id": "2",
      "name": "Bob Wilson",
      "email": "bob@student.com"
    }
  ]
}
```

### 2. Get Specific Student
```bash
curl -X GET http://localhost:8000/api/students/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "Alice Johnson",
    "email": "alice@student.com",
    "enrollments": []
  }
}
```

### 3. Create New Student
```bash
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@student.com"
  }'
```

**Expected Response (201 Created):**
```json
{
  "status": 201,
  "data": {
    "id": "507f1f77bcf86cd799439012",
    "name": "Alice Johnson",
    "email": "alice@student.com"
  },
  "message": "Student created successfully"
}
```

### 4. Update Student
```bash
curl -X PUT http://localhost:8000/api/students/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bob Wilson",
    "email": "bob@student.com"
  }'
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "Bob Wilson",
    "email": "bob@student.com"
  },
  "message": "Student updated successfully"
}
```

### 5. Delete Student
```bash
curl -X DELETE http://localhost:8000/api/students/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [],
  "message": "Student deleted successfully"
}
```

---

## 📚 Subjects Endpoints

### 1. List All Subjects
```bash
curl -X GET http://localhost:8000/api/subjects
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [
    {
      "id": "1",
      "name": "Mathematics",
      "course": "A1",
      "teacher": "John Doe"
    },
    {
      "id": "2",
      "name": "Physics",
      "course": "A1",
      "teacher": "Jane Smith"
    }
  ]
}
```

### 2. Get Specific Subject
```bash
curl -X GET http://localhost:8000/api/subjects/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "Mathematics",
    "course": "A1",
    "teacher": {
      "id": "1",
      "name": "John Doe"
    }
  }
}
```

### 3. Create New Subject
```bash
curl -X POST http://localhost:8000/api/subjects \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Chemistry",
    "course": "A1"
  }'
```

**Expected Response (201 Created):**
```json
{
  "status": 201,
  "data": {
    "id": "507f1f77bcf86cd799439013",
    "name": "Chemistry",
    "course": "A1",
    "teacher": null
  },
  "message": "Subject created successfully"
}
```

### 4. Update Subject
```bash
curl -X PUT http://localhost:8000/api/subjects/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Advanced Mathematics",
    "course": "B1"
  }'
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": {
    "id": "1",
    "name": "Advanced Mathematics",
    "course": "B1"
  },
  "message": "Subject updated successfully"
}
```

### 5. Delete Subject
```bash
curl -X DELETE http://localhost:8000/api/subjects/1
```

**Expected Response (200):**
```json
{
  "status": 200,
  "data": [],
  "message": "Subject deleted successfully"
}
```

---

## ⚠️ Error Responses

### Missing Required Fields (422)
```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{"name": "John"}'
```

**Response:**
```json
{
  "status": 422,
  "message": "Name and email are required",
  "errors": {
    "name": [],
    "email": ["Email is required"]
  }
}
```

### Route Not Found (404)
```bash
curl -X GET http://localhost:8000/api/invalid-route
```

**Response:**
```json
{
  "status": 404,
  "message": "Route not found"
}
```

---

## 🔧 Testing with Postman

1. **Create Collection**
   - Collection Name: "clientSchool API"

2. **Add Base URL Variable**
   - Key: `baseUrl`
   - Value: `http://localhost:8000`

3. **Add Requests** (use examples above)

4. **Test Each Endpoint**
   - GET requests: Just set URL
   - POST/PUT requests: Add JSON body with Content-Type header

---

## 📊 Testing Checklist

- [ ] `/api/health` returns 200
- [ ] `GET /api/teachers` returns 200 with data
- [ ] `GET /api/teachers/{id}` returns 200
- [ ] `POST /api/teachers` returns 201
- [ ] `PUT /api/teachers/{id}` returns 200
- [ ] `DELETE /api/teachers/{id}` returns 200
- [ ] `GET /api/students` returns 200 with data
- [ ] `POST /api/students` returns 201
- [ ] `GET /api/subjects` returns 200 with data
- [ ] `POST /api/subjects` returns 201
- [ ] Invalid routes return 404
- [ ] Missing fields return 422

---

## 📝 Notes

- All endpoints return JSON
- Status codes follow REST conventions
- Error messages are descriptive
- Dates in ISO 8601 format (if applicable)
  },
  "message": "Teacher created successfully"
}
```

### Update Teacher
```bash
curl -X PUT http://localhost:8000/api/teachers/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Jane Doe",
    "email": "jane.doe@school.com"
  }'
```

### Delete Teacher
```bash
curl -X DELETE http://localhost:8000/api/teachers/1
```

## Students Endpoints

### List All Students
```bash
curl -X GET http://localhost:8000/api/students
```

### Get Specific Student
```bash
curl -X GET http://localhost:8000/api/students/1
```

### Create New Student
```bash
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Alice Johnson",
    "email": "alice@student.com"
  }'
```

### Update Student
```bash
curl -X PUT http://localhost:8000/api/students/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Bob Wilson",
    "email": "bob@student.com"
  }'
```

### Delete Student
```bash
curl -X DELETE http://localhost:8000/api/students/1
```

## Subjects Endpoints

### List All Subjects
```bash
curl -X GET http://localhost:8000/api/subjects
```

### Get Specific Subject
```bash
curl -X GET http://localhost:8000/api/subjects/1
```

### Create New Subject
```bash
curl -X POST http://localhost:8000/api/subjects \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Mathematics",
    "course": "A1"
  }'
```

### Update Subject
```bash
curl -X PUT http://localhost:8000/api/subjects/1 \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Advanced Mathematics",
    "course": "A1"
  }'
```

### Delete Subject
```bash
curl -X DELETE http://localhost:8000/api/subjects/1
```

## Error Responses

### Missing Required Fields (422 Unprocessable Entity)
```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{"name": "John"}'
```

**Response:**
```json
{
  "status": 422,
  "message": "Name and email are required",
  "errors": {
    "name": [],
    "email": ["Email is required"]
  }
}
```

### Route Not Found (404)
```bash
curl -X GET http://localhost:8000/api/invalid-route
```

**Response:**
```json
{
  "status": 404,
  "message": "Route not found"
}
```

## Testing with Postman

1. **Import into Postman:**
   - Create a new Collection named "clientSchool API"
   - Add HTTP requests for each endpoint

2. **Set Base URL:**
   - Variable: `{{baseUrl}}`
   - Value: `http://localhost:8000`

3. **Test Each Endpoint:**
   - GET requests don't need body
   - POST/PUT requests need JSON body with Content-Type header

## Using Apidog (Alternative to Postman)

1. Create api project
2. Add API endpoints
3. Test requests with proper headers and body

## Test Results

All endpoints should return JSON responses with the following structure:
- **Success (200/201):** `{ "status": 200/201, "data": {...}, "message": "..." }`
- **Error (4xx/5xx):** `{ "status": 4xx/5xx, "message": "...", "errors": {...} }`
