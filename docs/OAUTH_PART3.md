# Part 3 - OAuth amb Google

## Objectiu

Afegir autenticació OAuth 2.0 al backend del projecte perquè l'usuari es validi amb Google i la nostra API generi un JWT propi.

Flux implementat:

1. L'usuari entra a `GET /auth/google`
2. El backend redirigeix a Google
3. Google torna a `GET /auth/callback?code=...&state=...`
4. El backend intercanvia el codi per l'usuari de Google
5. El backend crea o actualitza l'usuari a `auth_users`
6. El backend genera un JWT propi
7. El token es pot usar als endpoints protegits

## Rutes noves

- `GET /auth/google`
- `GET /auth/callback`
- `GET /auth/me`

## Endpoints protegits amb JWT

Els endpoints `POST`, `PUT` i `DELETE` de:

- `students`
- `teachers`
- `subjects`

requereixen capçalera:

```text
Authorization: Bearer TU_JWT
```

## Variables necessàries al `.env` de l'arrel

```env
GOOGLE_CLIENT_ID=posa_aqui_el_teu_client_id
GOOGLE_CLIENT_SECRET=posa_aqui_el_teu_client_secret
GOOGLE_REDIRECT_URI=http://127.0.0.1:8001/auth/callback

JWT_SECRET=canvia_aixo_per_un_secret_llarg_i_privrat
JWT_ISSUER=clientschool-api
JWT_TTL=3600
```

## Configuració a Google Cloud

1. Entra a `https://console.cloud.google.com/`
2. Crea un projecte nou
3. Ves a `APIs & Services`
4. A `Library`, activa `Google Identity Services`
5. Ves a `OAuth consent screen`
6. Tria `External`
7. Omple com a mínim:
   - App name
   - User support email
   - Developer contact information
8. Desa
9. Ves a `Credentials`
10. Clica `Create Credentials`
11. Tria `OAuth client ID`
12. Application type: `Web application`
13. Nom: per exemple `clientSchool Local`
14. Afegeix:

```text
Authorized redirect URI
http://127.0.0.1:8001/auth/callback
```

15. Desa i copia:
   - `Client ID`
   - `Client Secret`

## Important

La URL del callback ha de coincidir exactament amb la que tens al `.env`.

Si arranques el backend en un altre port, per exemple `8000`, també ho has de canviar a Google.

## Posada en marxa

```bash
cd ~/projectes/clientSchool
php -S 127.0.0.1:8001 -t public router.php
```

## Prova manual del flux

1. Obre:

```text
http://127.0.0.1:8001/auth/google
```

2. Inicia sessió amb Google
3. Accepta permisos
4. Google et redirigirà al callback
5. Veureu una pàgina amb el JWT generat

## Prova del token

Exemple per crear un student:

```bash
curl -X POST http://127.0.0.1:8001/api/students \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer TU_JWT" \
  -d '{"name":"OAuth Student","email":"oauth.student@example.com"}'
```

Exemple per consultar l'usuari autenticat:

```bash
curl http://127.0.0.1:8001/auth/me \
  -H "Authorization: Bearer TU_JWT"
```

## Persistència

La taula nova és:

- `auth_users`

Les taules `students`, `teachers` i `subjects` es mantenen com fins ara.

## Tests

Executar:

```bash
cd ~/projectes/clientSchool
vendor/bin/phpunit tests/Application/Auth/LoginHandlerTest.php tests/Application/Auth/TokenValidatorTest.php
```
