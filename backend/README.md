# Backend

La Part 1 es manté intacta a l'arrel del projecte per no alterar cap endpoint ni cap prova ja funcional.

## On és el backend real

- `public/index.php`
- `routes/api.php`
- `src/`
- `docs/API_BACKEND.md`

## Per què existeix aquesta carpeta

Serveix per presentar l'estructura `backend/` + `frontend/` que demana la segona part sense moure ni trencar la implementació original.

## Execució

També pots servir el backend des d'aquesta carpeta amb els wrappers inclosos:

```bash
php -S 127.0.0.1:8000 -t backend/public backend/router.php
```
