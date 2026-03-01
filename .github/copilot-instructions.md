# GitHub Copilot Instructions

## Project Overview
This is a **Somali Police System** built with Laravel 12 (PHP 8.2) and Tailwind CSS v4. It manages police stations, officers, crime records, investigations, court cases, and suspects.

## Tech Stack
- **Backend**: Laravel 12, PHP 8.2
- **Frontend**: Blade templates, Tailwind CSS v4, Vite
- **Database**: MySQL / SQLite (for testing)
- **Deployment**: Render (via `render.yaml`), Docker (`Dockerfile`)

## Code Style
- Follow PSR-12 coding standards for PHP
- Use Laravel conventions: Eloquent ORM, service classes in `app/Services/`, controllers in `app/Http/Controllers/`
- Blade templates live in `resources/views/`
- Keep controllers thin — move business logic to service classes under `app/Services/`
- Use route model binding where possible

## Key Models & Modules
- `User` — police officers and admins with role-based access
- `Station` — police stations managed by a `StationCommander`
- `Crime` / `PoliceCaseController` — crime reporting and case management
- `Investigation` / `InvestigationLog` — investigation tracking
- `Suspect` — suspect records linked to cases
- `CourtCase` — court case management linked to police cases
- `Facility` — facilities associated with stations
- `Audit` — audit trail for system actions

## Naming Conventions
- Controllers: `PascalCaseController`
- Models: `PascalCase` (singular)
- Migrations: `snake_case` with timestamps
- Routes: `kebab-case` for URIs, named using `dot.notation`

## Security
- All routes requiring authentication must use the `auth` middleware
- Role-based checks should use policy classes or middleware in `app/Http/Middleware/`
- Never expose sensitive `.env` values; use config helpers

## Testing
- PHPUnit tests live in `tests/`
- Run tests with `php artisan test`
