# ✅ Verificación Paso a Paso - clientSchool API

**Última actualización:** 12 April 2026  
**Estado:** ✅ Listo para validar

Sigue estos pasos en orden para verificar que todo funciona correctamente.

---

## 🚀 PASO 1: Preparación (1-2 minutos)

### 1.1 - Verifica que estés en el directorio correcto
```bash
cd /home/linux/projectes/clientSchool
pwd
```

**Esperado:**
```
/home/linux/projectes/clientSchool
```

### 1.2 - Verifica que las dependencias estén instaladas
```bash
ls -la vendor/ | head -5
```

**Esperado:** Verás carpetas como `autoload.php`, `composer/`, etc.

### 1.3 - Actualiza el autoloader
```bash
composer dumpautoload
```

**Esperado:**
```
Generating autoload files
Generated autoload files
```

---

## 🏗️ PASO 2: Verifica la estructura del proyecto (2-3 minutos)

### 2.1 - Verifica carpeta /src/
```bash
ls -la src/
```

**Esperado:**
```
Domain/
Application/
Infrastructure/
helpers.php
```

### 2.2 - Verifica Domain entities
```bash
ls src/Domain/
```

**Esperado:**
```
Course/
Enrollment/
Student/
Subject/
Teacher/
User/
```

**⚠️ NO DEBE HABER:** `Book/`, `Loan/` (son de libros, no de escuela)

### 2.3 - Verifica Controllers
```bash
ls src/Infrastructure/Web/*.php
```

**Esperado:**
```
Router.php
StudentController.php
SubjectController.php
TeacherController.php
```

### 2.4 - Verifica archivos principales
```bash
ls -la *.php
```

**Esperado:**
```
index.php          # Main entry point
router.php         # PHP server router
cli-test.php       # CLI testing
```

---

## 🧪 PASO 3: Tests CLI (Modo directo - Sin servidor)

### 3.1 - Test Health Endpoint
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/health php index.php
```

**Esperado:**
```json
{
    "status": 200,
    "data": {
        "status": "OK"
    }
}
```

### 3.2 - Test Root Endpoint
```bash
REQUEST_METHOD=GET REQUEST_URI=/ php index.php
```

**Esperado:**
```json
{
    "status": 200,
    "data": {
        "message": "clientSchool API v1.0"
    }
}
```

### 3.3 - Test List Teachers
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/teachers php index.php
```

**Esperado:** Array con 2 profesores:
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

### 3.4 - Test Get Single Teacher
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/teachers/1 php index.php
```

**Esperado:**
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

### 3.5 - Test List Students
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/students php index.php
```

**Esperado:** Array con estudiantes

### 3.6 - Test List Subjects
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/subjects php index.php
```

**Esperado:** Array con materias

### 3.7 - Test 404 Error
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/invalid php index.php
```

**Esperado:**
```json
{
    "status": 404,
    "message": "Route not found"
}
```

---

## 🌐 PASO 4: Tests con PHP Built-in Server (Opcional - con servidor)

### 4.1 - Inicia el servidor en otra terminal
```bash
cd /home/linux/projectes/clientSchool
php -S localhost:8000 -r router.php
```

**Esperado:**
```
[Sat Apr 12 10:00:00 2026] Development Server is running at http://0.0.0.0:8000
[Sat Apr 12 10:00:00 2026] Press Ctrl-C to quit.
```

### 4.2 - En otra terminal, prueba con curl
```bash
curl http://localhost:8000/api/health
```

**Esperado:**
```json
{
  "status": 200,
  "data": {
    "status": "OK"
  }
}
```

### 4.3 - Test Teacher endpoints
```bash
# List teachers
curl http://localhost:8000/api/teachers

# Get single teacher
curl http://localhost:8000/api/teachers/1

# Create teacher
curl -X POST http://localhost:8000/api/teachers \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Teacher","email":"test@school.com"}'
```

### 4.4 - Test Student endpoints
```bash
# List students
curl http://localhost:8000/api/students

# Create student
curl -X POST http://localhost:8000/api/students \
  -H "Content-Type: application/json" \
  -d '{"name":"Test Student","email":"test@student.com"}'
```

### 4.5 - Para el servidor
```bash
# En la terminal del servidor, presiona Ctrl+C
```

---

## 📋 PASO 5: Verifica Documentación (1-2 minutos)

### 5.1 - Verifica archivos .md
```bash
ls -la *.md
```

**Esperado:**
```
README.md              # API documentation
TESTING.md             # Testing guide
IMPLEMENTATION.md      # Implementation report
GITHUB_SETUP.md        # GitHub guide
```

### 5.2 - Verifica contenido README.md
```bash
head -20 README.md | grep -i "structure\|installation"
```

**Esperado:** Debe mencionar `/src/`, no `/app/`

### 5.3 - Verifica TESTING.md
```bash
head -30 TESTING.md | grep -i "quick start\|option"
```

---

## 🔧 PASO 6: Verifica Git (2 minutos)

### 6.1 - Verifica historial de commits
```bash
git log --oneline | head -5
```

**Esperado:**
```
7b7a556 refactor: reorganizar estructura a src/, eliminar duplicados...
1aa0e01 docs: agregar guía de configuración GitHub
33a1f0f docs: agregar documentación de implementación
e4ad266 feat(api): implementar endpoints REST
```

### 6.2 - Verifica estado del repositorio
```bash
git status
```

**Esperado:**
```
On branch master
nothing to commit, working tree clean
```

---

## 📊 PASO 7: Resumen Checklist

- [ ] ✅ Estructura en `/src/` correcta
- [ ] ✅ NO hay `/app/`
- [ ] ✅ NO hay `Book/` ni `Loan/` en Domain
- [ ] ✅ Controllers están en `src/Infrastructure/Web/`
- [ ] ✅ CLI test `/api/health` retorna 200
- [ ] ✅ CLI test `/api/teachers` retorna 200
- [ ] ✅ CLI test `/api/students` retorna 200
- [ ] ✅ CLI test `/api/subjects` retorna 200
- [ ] ✅ CLI test ruta inválida retorna 404
- [ ] ✅ README.md actualizado
- [ ] ✅ TESTING.md actualizado
- [ ] ✅ IMPLEMENTATION.md actualizado
- [ ] ✅ Git commits están bien

---

## 🎯 Resultado Final

Si todos los tests pasaron:

✅ **El proyecto está listo para presentación**

**Proximos pasos:**
1. Push a GitHub (ver GITHUB_SETUP.md)
2. Crear screenshots en Postman/Apidog
3. Preparar presentación de Parte 1

---

## 🆘 Troubleshooting

### Problema: Error de namespace
```
Fatal error: Class "App\Infrastructure\Web\Router" not found
```

**Solución:**
```bash
composer dumpautoload
```

### Problema: Funciones no definidas
```
Call to undefined function json_response()
```

**Solución:** Verifica que `src/helpers.php` existe y tiene las funciones.

### Problema: Archivo no encontrado
```
Directory ... does not exist
```

**Solución:** Verifica que estés en `/home/linux/projectes/clientSchool`

---

**Completado:** 12 April 2026  
**Próxima evaluación:** 16 April 2026 (Parte 1 - API)
