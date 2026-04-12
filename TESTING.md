# API Testing Examples - clientSchool

Estos son ejemplos de cómo testear la API clientSchool usando `curl`.

## Health Check

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

## Teachers Endpoints

### List All Teachers
```bash
curl -X GET http://localhost:8000/api/teachers
```

### Get Specific Teacher
```bash
curl -X GET http://localhost:8000/api/teachers/1
```

### Create New Teacher
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
    "id": "5e4f8c7a9b1d2c3e4f5a6b7c",
    "name": "John Doe",
    "email": "john.doe@school.com"
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
