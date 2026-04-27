# Client SPA Laravel - Part 2

**Document:** Evidencia de la Part 2 del projecte `clientSchool`  
**Data:** 27 d'abril de 2026  
**Estat:** Implementat

## Què s'ha afegit

- Carpeta `frontend/` amb una aplicació Laravel separada
- Autenticació OAuth amb GitHub via `laravel/socialite`
- Client SPA per consumir `students`, `teachers` i `subjects`
- Proxy Laravel cap al backend existent
- Tests funcionals del client

## Estructura

```text
backend/     -> wrapper per presentar i executar la Part 1 sense tocar-la
frontend/    -> aplicació Laravel SPA
docs/
  API_BACKEND.md
  CLIENT_SPA.md
```

## Variables necessàries al frontend

```env
SCHOOL_API_BASE_URL=http://127.0.0.1:8000
GITHUB_CLIENT_ID=...
GITHUB_CLIENT_SECRET=...
GITHUB_REDIRECT_URI=http://127.0.0.1:8080/auth/github/callback
```

## Posada en marxa

```bash
composer install
php -S 127.0.0.1:8000 -t public router.php

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
- Cal crear l'aplicació OAuth de GitHub abans de provar el login real
