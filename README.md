# clientSchool - REST API Backend

**Versió:** 1.0  
**Part:** 1 (API Backend)  
**Data:** 12 d'abril de 2026  
**Estat:** ✅ Completat

> REST API Backend per a la gestió d'una escola: estudiants, professors, assignatures i cursos.

---

## 📖 Documentació

Tota la documentació es troba en la carpeta `docs/`:

| Document | Descripció |
|----------|-----------|
| **[API_BACKEND.md](docs/API_BACKEND.md)** | 📋 Evidència de Part 1 - Endpoints, arquitectura i exemples |
| **[TESTING.md](docs/TESTING.md)** | 🧪 Guia completa de testing amb múltiples opcions |
| **[STEP_BY_STEP.md](docs/STEP_BY_STEP.md)** | 👣 Verificació pas a pas (opció ràpida en 30s) |
| **[STATUS.md](docs/STATUS.md)** | 📊 Resum complet del projecte i estat |
| **[IMPLEMENTATION.md](docs/IMPLEMENTATION.md)** | 🏗️ Detalls d'implementació i arquitectura DDD |
| **[GITHUB_SETUP.md](docs/GITHUB_SETUP.md)** | 🔧 Guia per configurar GitHub |

---

## 🚀 Inici Ràpid

### Opció 1: Verificació Completa (30 segons)
```bash
cd /home/linux/projectes/clientSchool
php verify-all.php
```

### Opció 2: Amb PHP Server
```bash
php -S localhost:8000 -r router.php
# Després: curl http://localhost:8000/api/health
```

### Opció 3: CLI Direct
```bash
REQUEST_METHOD=GET REQUEST_URI=/api/health php index.php
```

---

## ✨ Funcionalitats

- **19 Endpoints REST** - Completament funcionals i testats
- **Arquitectura DDD** - Domain, Application, Infrastructure layers
- **Validació de Dades** - Errors 422 per validació fallida
- **Manejo de 404** - Recursos no trobats retornen 404
- **JSON Responses** - Format consistente: `{status, data, message}`
- **Tests 100%** - 12/12 tests verificats

---

## 📋 Recursos

- **Students** - 5 endpoints (GET, POST, PUT, DELETE, GET {id})
- **Teachers** - 5 endpoints
- **Subjects** - 5 endpoints
- **Courses** - 2 endpoints (GET list, POST create)
- **Health Check** - `/api/health`

---

## 🏗️ Estructura del Projecte

```
src/
├── Domain/              # Entitats de negoci
│   ├── Teacher/
│   ├── Student/
│   ├── Subject/
│   ├── Course/
│   └── Enrollment/
├── Application/         # Use Cases
│   ├── CreateStudent/
│   ├── CreateTeacher/
│   └── ...
└── Infrastructure/Web/  # Controllers & Router
    ├── Router.php
    ├── *Controller.php
    └── ...
```

---

## 📈 Certificats de Qualitat

✅ **Tests**: 12/12 passat (100%)  
✅ **Estrutura**: DDD completa  
✅ **Documentació**: 7 fitxers .md (2422 línies)  
✅ **Git**: 8 commits descriptius  

---

## 🎯 Comandos Útils

| Comanda | Descripció |
|---------|-----------|
| `php verify-all.php` | Tests tots els endpoints |
| `php -S localhost:8000 -r router.php` | Inicia servidor |
| `php cli-test.php` | Tests CLI alternatiu |
| `git log --oneline` | Veure historial |
| `composer dumpautoload` | Regenerer autoload |

---

## 📞 Suport

**Per més informació, veure:**
- [API_BACKEND.md](docs/API_BACKEND.md) - Documentació principal
- [STEP_BY_STEP.md](docs/STEP_BY_STEP.md) - Tutorial pas a pas
- [TESTING.md](docs/TESTING.md) - Guia de testing

---

**Data de Lliurament:** 12 d'abril de 2026  
**Data d'Avaluació:** 16 d'abril de 2026  
**Projecte:** clientSchool Part 1 - API Backend
