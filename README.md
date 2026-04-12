# clientSchool - REST API

**School Management REST API** for Teachers, Students, and Subjects Management.

**Version**: 1.0.0  
**Status**: Part 1 (API Backend) - No OAuth yet

## Project Structure

```
clientSchool/
├── app/
│   ├── Http/
│   │   ├── Controllers/        # REST Controllers
│   │   │   ├── TeacherController.php
│   │   │   ├── StudentController.php
│   │   │   └── SubjectController.php
│   │   ├── Actions/            # Application Use Cases
│   │   ├── Router.php          # Simple REST Router
│   │   └── Middleware/
│   ├── Models/
│   │   └── Domain/             # DDD Domain entities
│   │       ├── Teacher/
│   │       ├── Student/
│   │       ├── Subject/
│   │       └── ...
│   ├── Infrastructure/         # Database & Persistence
│   └── helpers.php
├── public/
│   └── index.php               # API entry point
├── routes/
│   └── api.php                 # API routes definition
├── database/
│   ├── migrations/
│   └── seeds/
├── tests/
│   ├── Feature/                # API endpoint tests
│   └── Unit/                   # Unit tests
├── config/
│   └── database.php
├── storage/
├── .env
└── composer.json
```

## Installation

### Prerequisites
- PHP 8.1+
- Composer
- MySQL 8.0+ (optional, for persistence)

### Setup

1. **Install dependencies**
   ```bash
   cd /home/linux/projectes/clientSchool
   composer install
   ```

2. **Copy environment file** (already done)
   ```bash
   cp .env.example .env
   ```

3. **Generate application key** (if applicable)

4. **Run server**
   ```bash
   php -S localhost:8000 -t public
   ```

The API will be available at: `http://localhost:8000/api`

## API Endpoints

### Health Check
- `GET /api/health` - Check API status

### Teachers
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/teachers` | List all teachers |
| GET | `/api/teachers/{id}` | Get specific teacher |
| POST | `/api/teachers` | Create new teacher |
| PUT | `/api/teachers/{id}` | Update teacher |
| DELETE | `/api/teachers/{id}` | Delete teacher |

### Students
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/students` | List all students |
| GET | `/api/students/{id}` | Get specific student |
| POST | `/api/students` | Create new student |
| PUT | `/api/students/{id}` | Update student |
| DELETE | `/api/students/{id}` | Delete student |

### Subjects
| Method | Endpoint | Description |
|--------|----------|-------------|
| GET | `/api/subjects` | List all subjects |
| GET | `/api/subjects/{id}` | Get specific subject |
| POST | `/api/subjects` | Create new subject |
| PUT | `/api/subjects/{id}` | Update subject |
| DELETE | `/api/subjects/{id}` | Delete subject |

## Request/Response Format

### Request
```bash
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{
    "name": "John Doe",
    "email": "john@school.com"
  }'
```

### Response Success (201)
```json
{
  "status": 201,
  "data": {
    "id": "507f1f77bcf86cd799439011",
    "name": "John Doe",
    "email": "john@school.com"
  },
  "message": "Teacher created successfully"
}
```

### Response Error (422)
```json
{
  "status": 422,
  "message": "Name and email are required",
  "errors": {
    "name": ["Name is required"],
    "email": ["Email is required"]
  }
}
```

## Testing with Postman/Apidog

### Import Collection
1. Open Postman or Apidog
2. Create a new collection "clientSchool API"
3. Add the requests from the Endpoints table above

### Example Test (Teachers)

**POST Create Teacher**
- URL: `http://localhost:8000/api/teachers`
- Method: POST
- Body (JSON):
  ```json
  {
    "name": "John Doe",
    "email": "john@example.com"
  }
  ```

**GET List Teachers**
- URL: `http://localhost:8000/api/teachers`
- Method: GET

**GET Single Teacher**
- URL: `http://localhost:8000/api/teachers/1`
- Method: GET

## Running Tests

```bash
chmod +x vendor/bin/phpunit
vendor/bin/phpunit
```

Or using composer:
```bash
composer test
```

## Environment Variables

| Variable | Default | Description |
|----------|---------|-------------|
| `APP_NAME` | clientSchool | Application name |
| `APP_ENV` | local | Environment (local/production) |
| `APP_DEBUG` | true | Debug mode |
| `DB_HOST` | localhost | Database host |
| `DB_PORT` | 3306 | Database port |
| `DB_DATABASE` | clientschool | Database name |
| `DB_USERNAME` | root | Database user |
| `DB_PASSWORD` | (empty) | Database password |

## Coming Soon - Part 2

- OAuth 2.0 Authentication
- Advanced filtering and pagination
- Database persistence with Doctrine ORM
- Comprehensive test suite

## License

MIT
