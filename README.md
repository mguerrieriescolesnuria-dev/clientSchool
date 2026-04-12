# clientSchool

**School Management REST API** - Teachers, Students, Subjects

**Version:** 1.0  
**Status:** ✅ Part 1 Complete

---

## 🚀 Quick Start

Verify all endpoints in 30 seconds:

```bash
php verify-all.php
```

Expected result:
```
🎉 ¡TODAS LAS PRUEBAS PASARON!
✅ El proyecto estructura está correctamente
✅ Todos los endpoints responden correctamente
✅ El manejo de errores funciona
```

---

## 📖 Full Documentation

**[docs/API_BACKEND.md](docs/API_BACKEND.md)** - Complete API documentation
- 19 REST Endpoints
- DDD Architecture
- Examples for Postman/Apidog

---

## 🧪 Testing

```bash
# PHP Server
php -S localhost:8000 -r router.php
curl http://localhost:8000/api/health

# CLI Direct
REQUEST_METHOD=GET REQUEST_URI=/api/health php index.php

# Automated Tests
php verify-all.php
```

---

## ✨ Features

- 19 REST Endpoints (100% tested)
- DDD Architecture
- Data Validation (422 errors)
- Proper 404 handling
- JSON responses

---

## 📚 Resources

- Students (5 endpoints)
- Teachers (5 endpoints)
- Subjects (5 endpoints)
- Courses (2 endpoints)

---

**Complete documentation:** [docs/API_BACKEND.md](docs/API_BACKEND.md)
