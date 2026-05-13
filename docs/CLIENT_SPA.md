# Client Laravel - Part 2 i Part 3

**Document:** Evidencia de la Part 2 del projecte `clientSchool`  
**Data:** 27 d'abril de 2026  
**Estat:** Implementat

## Què s'ha afegit

- Carpeta `frontend/` amb una aplicació Laravel separada
- Autenticació bàsica amb usuari i contrasenya
- Integració amb Google OAuth a través del backend
- Client Laravel per consumir `students`, `teachers` i `subjects`
- Proxy Laravel cap al backend existent
- Tests funcionals del client
- Dashboard amb formularis normals per crear, editar i eliminar registres

## Estructura

```text
backend/     -> wrapper per presentar i executar la Part 1 sense tocar-la
frontend/    -> aplicació Laravel client
docs/
  API_BACKEND.md
  CLIENT_SPA.md
```

## Variables necessàries al frontend

```env
SCHOOL_API_BASE_URL=http://127.0.0.1:8001
```

## Posada en marxa

```bash
composer install
php -S 127.0.0.1:8001 -t public router.php

cd frontend
cp .env.example .env
composer install
php artisan key:generate
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8082
```

## Tests

```bash
cd frontend
php artisan test
```

## Notes

- La Part 1 no s'ha modificat funcionalment
- El client es pot obrir amb login bàsic o amb Google OAuth
- Per fer CRUD sobre l'API protegida, el client necessita el JWT obtingut després del login amb Google
