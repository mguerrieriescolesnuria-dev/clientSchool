# Client Laravel - Part 2

**Document:** Evidencia de la Part 2 del projecte `clientSchool`  
**Data:** 27 d'abril de 2026  
**Estat:** Implementat

## Què s'ha afegit

- Carpeta `frontend/` amb una aplicació Laravel separada
- Autenticació bàsica amb usuari i contrasenya
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
npm install
php artisan key:generate
php artisan migrate
npm run build
php artisan serve --host=127.0.0.1 --port=8080
```

## Tests

```bash
cd frontend
php artisan test
```

## Notes

- La Part 1 no s'ha modificat funcionalment
- El client d'aquesta entrega usa login bàsic i dashboard CRUD sense JavaScript obligatori
