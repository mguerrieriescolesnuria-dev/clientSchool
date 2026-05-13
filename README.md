# clientSchool

Projecte de DAW M0613 dividit en tres parts:

- Part 1: API REST al backend
- Part 2: client SPA en Laravel
- Part 3: autenticació OAuth amb Google i JWT propi

## Estructura

```text
clientSchool/
├── public/                  # punt d'entrada del backend API
├── src/                     # domini, aplicació, persistència i OAuth
├── frontend/                # client Laravel
├── docs/
│   ├── API_BACKEND.md
│   └── CLIENT_SPA.md
└── backend/                 # carpeta de suport per a l'entrega
```

## Què hi ha implementat

### Backend API

- Recursos `students`, `teachers` i `subjects`
- Endpoints REST per llistar, crear, editar i eliminar
- Persistència MySQL
- Taules creades automàticament:
  - `students`
  - `teachers`
  - `subjects`
  - `auth_users`

### Frontend Laravel

- Login bàsic amb email i contrasenya
- Accés amb Google OAuth
- Dashboard per veure `students`, `teachers` i `subjects`
- CRUD complet quan la sessió del client té JWT de backend

### OAuth

- Ruta backend `GET /auth/google`
- Callback backend `GET /auth/callback`
- JWT propi generat per l'aplicació
- Ruta `GET /auth/me`
- Protecció amb token a:
  - `POST /api/students`, `PUT`, `DELETE`
  - `POST /api/teachers`, `PUT`, `DELETE`
  - `POST /api/subjects`, `PUT`, `DELETE`

## Configuració necessària

### Backend `.env`

```env
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientschool_frontend
DB_USERNAME=clientschool
DB_PASSWORD=1234

GOOGLE_CLIENT_ID=el_teu_client_id
GOOGLE_CLIENT_SECRET=el_teu_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8001/auth/callback

JWT_SECRET=un_secret_llarg_i_privrat
JWT_ISSUER=clientschool-api
JWT_TTL=3600

OAUTH_FRONTEND_SUCCESS_REDIRECT=http://127.0.0.1:8082/auth/google/callback
```

### Frontend `frontend/.env`

```env
APP_URL=http://127.0.0.1:8082

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=clientschool_frontend
DB_USERNAME=clientschool
DB_PASSWORD=1234

SCHOOL_API_BASE_URL=http://127.0.0.1:8001
SCHOOL_API_TIMEOUT=10
```

## Arrencada

### 1. Backend

```bash
cd ~/projectes/clientSchool
php -S 127.0.0.1:8001 -t public router.php
```

### 2. Frontend

```bash
cd ~/projectes/clientSchool/frontend
composer install
php artisan migrate
php artisan serve --host=127.0.0.1 --port=8082
```

## Com provar OAuth

### Opció 1: des del frontend

1. Obre `http://127.0.0.1:8082`
2. Clica `Entrar amb Google`
3. Google redirigeix al backend
4. El backend torna al frontend
5. El client guarda el JWT a sessió i entra al dashboard

### Opció 2: des del backend

1. Obre `http://127.0.0.1:8001/auth/google`
2. Inicia sessió amb Google
3. El backend genera el JWT

## Tests

### Backend OAuth

```bash
cd ~/projectes/clientSchool
vendor/bin/phpunit tests/Application/Auth/LoginHandlerTest.php tests/Application/Auth/TokenValidatorTest.php
```

### Frontend Laravel

```bash
cd ~/projectes/clientSchool/frontend
php artisan test
```

## Notes

- La base de dades no es puja a GitHub; només s'hi puja el codi
- Si una altra persona clona el projecte, ha de crear la seva base de dades i configurar els `.env`
- El client continua permetent login bàsic, però el CRUD del backend protegit necessita el JWT obtingut amb Google OAuth
