# Full-Stack Interview Task — CodeIgniter 4 + React

This repository implements the assignment:

- CodeIgniter 4 backend with JWT-based auth (register, login) and protected APIs
- MySQL by default (PostgreSQL notes included)
- Two tables with 1–1 relation: `auth_user` and `teachers`
- **Single POST API** to create user + teacher together in one request
- React (Vite) frontend showing auth module and datatables for both tables
- Docker setup to bootstrap CodeIgniter 4 automatically

## Quick Start

### 1) Backend (Docker)

```bash
cd backend
# Build & run (first run takes a few minutes because it scaffolds CI4 inside the container)
docker compose up --build
```
- API base URL: `http://localhost:8080`
- Adminer (DB UI): `http://localhost:8081` (server: `db`, user: `root`, pass: `rootpass`, db: `ci_app`)

### 2) Frontend

```bash
cd ../frontend
npm install
npm run dev
```
- Frontend URL: shown by Vite (usually `http://localhost:5173`). It talks to `http://localhost:8080`

## API Summary

- `POST /api/register` — Create a user
- `POST /api/login` — Get a JWT
- `GET /api/me` — Get current user (JWT required)
- `POST /api/users-with-teacher` — **Single** API to insert into `auth_user` and `teachers`
- `GET /api/auth-users` — List users (JWT required)
- `GET /api/teachers` — List teachers (JWT required)

## Migrations

Migrations define `auth_user` and `teachers`. Run inside backend container:

```bash
docker compose exec app php spark migrate
```

## Switch to PostgreSQL

- Change `DB_*` env variables in `backend/.env` (examples included)
- Adjust `docker-compose.yml` to use postgres service (a sample is provided, commented out).

## Notes

- The Dockerfile auto-installs CodeIgniter 4 (appstarter) via Composer during build, then overlays the customized app files (controllers, models, routes, filters, migrations).
- JWT uses `firebase/php-jwt`.
- Passwords hashed with `password_hash(PASSWORD_DEFAULT)`.

