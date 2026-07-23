# Hideout Coffee POS

## Stack

- **Laravel 12** + MySQL (`hideout_coffe`), session/queue/cache all `database` driver
- **Blade** (`@yield` section pattern) + **Tailwind v3** (PostCSS, NOT `@tailwindcss/vite`) + **Chart.js** (CDN) + **Alpine.js** + **Preline UI**
- **Spatie RBAC**: 2 roles — `owner`, `kasir`; role middleware aliased in `bootstrap/app.php:17`
- **No API routes**; web-only (routes in `routes/web.php`, which `require`s `routes/auth.php`)

## Dev commands

| Action | Command |
|--------|---------|
| Full stack | `composer dev` (artisan serve + queue:listen + pail + Vite via concurrently) |
| PHP tests | `php artisan test` |
| Single test | `php artisan test --filter=TestName` |
| PHP lint | `./vendor/bin/pint` |
| Vite dev | `npm run dev` |
| Migrate + seed | `php artisan migrate:fresh --seed` |

## Key architecture

- **Owner** (`/owner/*`, `role:owner`): CategoryController, MenuController, CashierController, Owner\TransactionController, PromoController
- **Kasir** (`/kasir/*`, `role:kasir`): TransactionController (empty stub)
- **Auth**: login uses `username`, validates `status !== 'inactive'`, redirects by role — see `LoginRequest.php`, `AuthenticatedSessionController.php`
- **User model**: `username`, `first_name`, `last_name`, `email`, `phone`, `password`, `status` — `HasRoles` trait; `name` column does not exist
- **Layouts**: `layouts.app` (`@yield`, Inter, `lang="id"`), `layouts.guest` (Figtree, `{{ $slot }}`)
- **Root `/`** redirects by role or to login
- **Seed users**: `mhdhsbyy` (kasir) / `daniel` (owner), both `password`

## Frontend conventions

- **Custom Tailwind colors** (use these in new views): `primary` (#764706), `secondary` (#ADA68A), `soft` (#FFF0DA), `light` (#F1F1F1), `dark` (#191919) — defined in `tailwind.config.js`
- **Fonts**: `Inter` for main layout (`font-inter`), `Figtree` as default sans — Google Fonts loaded in `layouts.app`
- **Chart.js** loaded from CDN in `layouts.app`, not via npm
- **Alpine.js** powers interactive views (POS cart, sidebar toggle) — inline `<script>` blocks, not compiled
- **Code style**: 4-space indent (PHP/Blade), 2-space (YAML), LF line endings — see `.editorconfig`; PHP lint via `./vendor/bin/pint`

## Quirks & gotchas

- **Registration broken**: `RegisteredUserController` validates `name` (column gone), `resources/views/auth/register.blade.php` does not exist
- **Dead layout code**: `layouts/default/` directory is unused — do not import from it. `navigation.blade.php` (in `layouts/`) is also dead — no active layout includes it. It references `Auth::user()->name` (column gone) and `route('dashboard')` (doesn't exist)
- **Tailwind v3 only**: `postcss.config.js` uses `tailwindcss` + `autoprefixer`. `@tailwindcss/vite` (v4) is in `package.json` but NOT wired in `vite.config.js`. Do not use v4 syntax (`@import "tailwindcss"`, CSS-first config)
- **Preline** imported in `resources/js/app.js` — initializers must run after DOM mutations; may conflict with Alpine
- **Kasir dashboard empty**: just `@extends('layouts.app')` with no content
- **Sidebar owner-only**: `layouts.part.sidebar` hardcodes `owner.*` routes; kasir sees dead links
- **Tests broken**: `phpunit.xml` has SQLite env vars commented out (needs MySQL or uncomment); auth tests use `email` for login and `route('dashboard')`, not matching current auth (`username` field, `owner.dashboard`/`kasir.dashboard`)
- **`.env.example` defaults to SQLite** but `.env` uses MySQL — fresh clones must update. Also `.env.example` has `APP_LOCALE=en` vs `.env`'s `APP_LOCALE=id`
- **3-layer discount**: (1) per-item flat discount, (2) manual transaction discount, (3) auto-applied promos (percentage/flat, scoped to item/category/transaction, with date range). Active promos split into "Aktif", "Kadaluarsa", "Nonaktif" sections on index page.
- **Promo auto-apply**: evaluated client-side via Alpine in POS create view; server recalculates in `TransactionController::store()`
- **Menu photo storage**: `storage/app/public/menus/{category-slug}/` via `public` disk; old photos deleted on update/destroy
- **Indonesian locale**: `APP_LOCALE=id`, auth errors in Bahasa
- **Seeder order matters**: `UserSeeder` creates roles first — must run before other seeders
- **No CI**: no `.github/` directory
