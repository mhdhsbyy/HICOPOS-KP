# Hideout Coffee POS

Laravel 12 POS for a coffee shop. Two roles: `owner` (admin + own POS) and `kasir`. All UI copy is Indonesian. `README.md` is stock Laravel — this file is the real source of truth.

## Stack

- Laravel 12, PHP ^8.2, MySQL `hideout_coffe`. Session, cache and queue all use the `database` driver, so if MySQL is down the whole app 500s, not just writes.
- Blade (`@yield` sections) + Tailwind v3 (PostCSS) + Alpine (npm, started in `resources/js/app.js`) + Preline. Chart.js, SweetAlert2, animate.css and anime.js are CDN-only.
- Spatie RBAC, exactly two roles; `role` middleware aliased in `bootstrap/app.php:18`.
- Web routes only (no `routes/api.php`). The JSON "API" endpoints are filters on web routes.
- PDFs via `barryvdh/laravel-dompdf`.

## Commands

| Action | Command |
|---|---|
| Full stack | `composer dev` (serve + queue + pail + Vite) |
| Migrate + seed | `php artisan migrate:fresh --seed` — wipes `hideout_coffe`, which is on a shared MySQL server |
| PHP lint | `./vendor/bin/pint` (`--test` to check only) |
| Vite | `npm run dev` / `npm run build` |
| Regenerate menu thumbs | `php artisan menus:thumbnails` (optional `{menuId?}`; needs GD + WebP) |
| Menu photo symlink | `php artisan storage:link` — required, views use `Storage::url()` |
| Migration state | `php artisan migrate:status` (safe; `db:show` counts tables across all schemas) |
| Tests | `php artisan test` / `--filter=Name` — see *Testing* |

First-time setup: `composer install` → `npm install` → fix `.env` (`.env.example` is wrong) → `php artisan key:generate` → `migrate:fresh --seed` → `storage:link`.

Seed logins (password `password`): `mhdhsbyy`, `zein` (kasir), `daniel` (owner, and the only owner `MenuStoreProbeTest` accepts).

## Architecture

- **Owner** (`/owner/*`, `role:owner`): invokable `DashboardController`; resource controllers for `categories`, `menus`, `cashiers`, `promos`; `menus`, `cashiers`, `promos` also have `PATCH toggle-status` (categories does not). `Owner\TransactionController` has hand-written routes for `index`, `index-filter`, `chart-filter`, `print-index`, `create`, `store`, `page` (`?tab=create|history`), `history`, `history-filter`, `load-more-menus`, `show`, `kasir-show`, `receipt-pdf`, `cancel`.
- `index()`/`indexFilter()` show **all** transactions with global stats (`cashier_id` filter over `User::role(['kasir','owner'])`). `history()`/`historyFilter()` are scoped to `Auth::id()`.
- **Kasir** (`/kasir/*`, `role:kasir`): invokable `Cashier\DashboardController` + `Cashier\TransactionController`. Views live in `resources/views/cashier/` — there is no `views/kasir/`. Dashboard today-counts/chart/recent rows are scoped to `Auth::id()`; top-5 products and active-menu count are global.
- `owner.kasir.dashboard` (`GET /owner/kasir-dashboard`) runs `Cashier\DashboardController` with the kasir dashboard view. `$isOwner` is **not** passed by the controller; shared views derive it themselves (`cashier/dashboard.blade.php:4`, `cashier/transactions/history.blade.php:4`, `_history_table.blade.php:2`).
- **Shared partials**: `transactionsQuery()` + `owner/transactions/_data_table.blade.php` back `index()`/`indexFilter()`; `printIndex()` reuses the same query. Owner `history()`/`historyFilter()` render `cashier.transactions.history` and `_history_table`, scoped to `Auth::id()`, with links flipped by `$isOwner` (owner → `owner.transactions.kasir-show`).
- `Owner\TransactionController::page()`'s history tab is a **separate inline table** in `owner/transactions/page.blade.php` (hardcoded `owner.transactions.show` links) — changes to `_history_table` do not propagate; keep them in sync by hand.
- Promo menu picker is shared via `owner/promos/_menu_multi_select.blade.php`; it owns the `menuMulti()` Alpine component and the `JSON_HEX_TAG`-encoded menu payload.
- **Ownership guards are asymmetric**: `Owner\TransactionController::show/kasirShow/receiptPdf/cancel` have no `user_id` check; the `Cashier\TransactionController` equivalents return 403 for another user's transaction. Mirror the side you are editing.

### Layouts

| Layout | Chrome | Used by |
|---|---|---|
| `layouts.app` | Inter, `lang="id"`, sidebar + header; sidebar chosen by role only | all owner admin pages, `owner.transactions.page` |
| `layouts.app-nosidebar` | `header-kasir` + collapsible nav, no sidebar | kasir dashboard/create/history/show, owner create, profile-in-kasir-mode |
| `layouts.guest` | Figtree, `{{ $slot }}` | login / forgot / reset |

- There is no global `hasRole()` Blade helper; always use `Auth::user()->hasRole('x')`.
- `layouts.app` picks the sidebar by role only, so an owner browsing kasir mode still gets the admin sidebar — that is why `owner.transactions.page` and `profile/edit.blade.php` exist. `profile/edit.blade.php` chooses its own layout (`layouts.app-nosidebar` for kasir or `?from=kasir`, else `layouts.app`).
- Dead code, do not import: `resources/views/layouts/default/` and `resources/views/layouts/navigation.blade.php` (unreferenced, call nonexistent `route('dashboard')`).

### Domain notes

- **`User` has no `name` column.** Login is by `username` (`app/Http/Requests/Auth/LoginRequest.php`). Use `$user->full_name` (falls back to username) and `avatar_url` (falls back to `images/logo-ho.png` — don't hardcode the logo). `status === 'inactive'` is rejected at login. Login redirects by role to `owner.dashboard`/`kasir.dashboard`; `auth/login.blade.php` merges the two required-field errors into one ("Username dan password wajib diisi.") — keep them in sync. Forgot/reset still uses email.
- **Menu `code`**: nullable unique, `{CATEGORY-INITIALS}-###`, ordered by `code`. `Menu::generateCode()` skips collisions with trashed rows; `Menu::reindexCodes()` nulls all codes in the category (including trashed) before renumbering. Call it after menu delete or category move.
- **Transaction `code` is derived** (`TRX-0003-KO-NO`), and the category suffix only appears when `items` is loaded (`Transaction::getCodeAttribute` checks `relationLoaded('items')`). Eager-load `items.menu.category` or you get a bare `TRX-####`.
- **`transactions.customer_name`** is nullable. The three POS views post it via Alpine `customerName` + hidden input, persist it in `localStorage.pos_state`, and the receipt/report/show/dashboard views render it. The POS persist handler must be `savePosState()`; `saveCart()` writes only `pos_cart`.
- **Menu photos** go to `public` disk under `menus/{category-slug}/`; views use `thumb_url` (WebP generated by the `Menu::saved` hook via `GenerateMenuThumbnails`). Update replaces old files; soft delete keeps them so receipts/history still render.
- **Profile** is outside the role groups: `profile.edit/update`, separate `profile.photo` (`avatars/` on public disk, old file removed, `image|mimes:jpg,jpeg,png,webp|max:2048`), password posts to `password.update`.
- **Soft deletes** on `User`, `Category`, `Menu`, `TransactionItem`, `Promo`. Relations use `withTrashed()`: `Transaction::user`, `Menu::category`, `Promo::category`, `Promo::menus`, `TransactionItem::menu`, `Transaction::promos` — without these, receipts/show pages crash or silently lose lines after a delete.
- **Never `forceDelete()` a menu that has transaction items** — `transaction_items.menu_id` cascades and erases history. Same cascade hazard for users (`transactions.user_id`) and categories (`menus.category_id`), which is why all three use soft deletes. Destroy methods deliberately have no relation guard and do not delete photos.
- Trashed menus stay visible/editable in owner listings because `MenuController::index()` loads `Category::withTrashed()` and groups by name; `edit()` pushes a ghost category back into the dropdown. POS chips use `Category::has('menus')`, so ghost categories disappear from the chips.
- `unique:users` and `unique:categories` count trashed rows. `menus_code_unique` counts trashed rows too, hence the null-then-reindex logic.
- Promo soft delete keeps `transaction_promos` pivot rows (soft delete never fires the FK cascade) and `Promo::active()` is scoped, so deleted promos stop applying to new sales while receipts keep their promo lines.

## POS / discounts

Three layers, applied in order: per-item flat discount → auto promos → manual transaction discount.

Promo math is duplicated in **5 files — change all of them**: Alpine `posApp()` in `cashier/transactions/create.blade.php`, `owner/transactions/create.blade.php`, `owner/transactions/page.blade.php`, plus server recalc in both `TransactionController::store()` methods. The server revalidates via `Promo::active()` + `calculatePromoDiscount()` and always wins.

- Promos are `percentage` or flat `value`, scoped `item` / `category` / `transaction`, gated by `min_quantity`/`min_amount`, windowed by datetime `start_date`/`end_date` (null = unbounded).
- Item scope targets menus via `promo_menus` (`menu_ids[]`, validated `required_if:scope,item`); `target_id` is category-only. Every POS fetch must eager-load `menus` (all three `@json($promos)` payloads and both store recalc queries), because the client filters `cartItems` against `promo.menus` ids.
- Cart and applied promos post as JSON strings in hidden inputs; `store()` detects strings and `$request->merge(json_decode(..., true))` before validating.
- Payment methods, in exact `in:` order: `cash,debit,kredit,transfer,qris`. UI order must stay CASH → DEBIT → KREDIT → TRANSFER → QRIS. Debit/kredit share the EDC modal. QRIS shows `public/images/qris.jpeg` and forces `amount_paid = total`.
- Transfer has its own bank modal in all three POS views. Bank list comes from `config/hideout.php` via `@json(config('hideout.bank_accounts'))` (placeholder accounts — the owner must replace them); state uses `showBankModal` / `bankAccounts` / `selectedBankIndex` and `bankTransferPaid()`. The chosen bank is **not persisted** — only `payment_method = 'transfer'`.
- `page.blade.php` has no QRIS modal and posts `amount_paid` unforced, so QRIS cannot be completed there.
- When `print_receipt` is truthy, `store()` returns the receipt PDF instead of redirecting; otherwise it redirects with a Swal toast.
- POS state persists in `localStorage` under `pos_cart` / `pos_state`; `confirmLogout()` clears both.

### PDFs — two independent paths

1. **Receipts** → `App\Support\ReceiptBuilder::toResponse()`, `views/pdf/receipt.blade.php`, 80mm, returned inline. It binary-searches the smallest single-page height because DomPDF has no content-height API — do not simplify that.
2. **Report** → `Owner\TransactionController::printIndex()` builds a raw `new Dompdf`, A4 landscape, view `owner.transactions.print`, route `owner.transactions.print-index` → `laporan-transaksi-*.pdf`. Do not route this through `ReceiptBuilder`.

### Route ordering

Literal routes must be registered before `Route::resource`'s `show`/`{model}`. `menus.load-more` is currently after the menus resource, so `GET /owner/menus/load-more` is dead (404) and nothing calls it. On both roles, `receipt-pdf` and `load-more-menus` are correctly before `transactions/{transaction}` — keep it that way.

## Frontend

- Custom Tailwind colors (use these, no invented hex): `primary #764706`, `secondary #ADA68A`, `soft #FFF0DA`, `light #F1F1F1`, `dark #191919`. Fonts: `font-inter`; Figtree is loaded only by `layouts.guest`, so it will not resolve on app pages.
- Charts are driven by HTML data attributes (`data-labels` / `data-values`) parsed by inline layout scripts. Only `owner/dashboard`, `owner/transactions/index` and `cashier/dashboard` render `#chart`; a second `#chart` canvas gets a duplicate instance. Dashboard "Produk Terlaris" widgets are CSS bars.
- CDN order differs: in `layouts.app` Chart.js loads before `@stack('scripts')`; in `layouts.app-nosidebar` it loads after, so a `@push('scripts')` block using `Chart` breaks there. `anime.js` is only in `layouts.app`.
- Global JS helpers (`confirmDelete`, `confirmCancel`, `blockedDelete`, `confirmLogout`, `hoCountUp`) are duplicated verbatim in both app layouts — edit both or neither. `#produkChart` in `layouts/app.blade.php` is dead.
- POS logic lives in per-view inline `<script>` blocks. Use raw `fetch`; `window.axios` exists but is unused. Preline initializers may conflict with Alpine and must rerun after DOM mutations.
- 4-space indent (PHP/Blade/JS), LF. No ESLint/Prettier/stylelint configured.

## Testing

`php artisan test` **wipes the dev database**: `phpunit.xml:25-26` leaves the SQLite env lines commented out, so `RefreshDatabase` runs against the shared MySQL `hideout_coffe`. Seven files use it (`AuthenticationTest`, `EmailVerificationTest`, `PasswordConfirmationTest`, `PasswordResetTest`, `PasswordUpdateTest`, `RegistrationTest`, `ProfileTest`). Uncomment both lines before running the suite. `tests/Feature/ExampleTest.php` only *looks* like an eighth — its import is commented out.

No test currently passes — they all predate the `name` → `username` rename and broken `route('dashboard')`:

- `UserFactory` sets `name` (not a column).
- `AuthenticationTest`, `RegistrationTest`, `EmailVerificationTest` assert `route('dashboard')`.
- Auth/profile tests post `email`/`name` instead of `username`/`first_name`.
- `tests/Feature/ExampleTest.php` expects `/` = 200, but `/` always redirects.
- `tests/Unit/ExampleTest.php` is `assertTrue(true)`; `tests/TestCase.php` is untouched skeleton.

`MenuStoreProbeTest` is not a test: zero assertions, no `RefreshDatabase`, queries the live DB for `daniel`, and dumps HTML to STDERR.

## Known bugs — do not "fix" blindly

1. **Promo double application** multiplies the discount in both `store()` methods: the loop adds on every duplicate `promo_id`, while pivot rows dedupe. Receipt then can't reconcile (pivot amount vs multiplied total).
2. **Manual transaction discount is uncapped** (`'discount' => 'nullable|numeric|min:0'`, read at `Owner/TransactionController.php:471` and `Cashier/TransactionController.php:129`). `max(0, ...)` only floors it, so a kasir can post `discount = subtotal` for a free sale. Per-item discounts *are* capped server-side (`Owner:435`, `Cashier:93`) — don't touch that.
3. **`CashierController` route-model binding is not scoped to kasir**: `edit/update/destroy/toggleStatus` (`:75,80,122,134`) accept any user id, so an owner can edit/soft-delete the owner account (self-delete is blocked).
4. **Stats ignore `status` inconsistently**: owner `transactionsStats()`/`historyStats()` (`:176`, `:575`) use `$request->status` unvalidated, cashier `historyStats()` (`:236`) hardcodes `completed`; `page()`'s `$todayTransactions` (`:62`) omits `completed` while `$todayRevenue` includes it.
5. **Inactive menus stay sellable** if already in `localStorage.pos_cart`: `store()` uses `Menu::findOrFail()` with no status check.
6. **Stale promo is silently dropped** (`continue`), leaving a discount on screen that the server rejects; retries fail identically.
7. **Client trusts cached `category_id`/`price`** from localStorage while the server re-reads the DB; moving/repricing a menu under a live cart produces the same misleading payment error.
8. **`bindHistoryEvents()` re-binds on every `fetchHistory()`** (`cashier/transactions/history.blade.php:344`, defined `:375`), accumulating listeners. Not visible because of debounce.

Recently fixed, don't re-introduce: `/owner/transactions-page` 500 (now passes `$categories`), duplicated "Proses Pembayaran" node + stray `</button>` in `page.blade.php`, and missing `transfer` in the report's `$paymentLabels`.

Minor: `addslashes()` in inline Alpine handlers is not XSS-exploitable (Blade escapes after) but a newline in a menu name breaks the handler — 6 sites across `cashier/transactions/create.blade.php:63`, both `_menu_cards.blade.php:3`, `owner/transactions/create.blade.php:63`, `owner/transactions/page.blade.php:71-72`. `store()` error messages echo `$e->getMessage()` (`Owner:518`, `Cashier:176`). Pint reports 2 style-only failures: `Owner/DashboardController.php` (EOF newline) and `2026_09_12_000000_add_photo_to_users_table.php` (formatting).

## Gotchas

- **`route('dashboard')` does not exist** and is called by 6 Breeze controllers (`ConfirmablePasswordController:38`, `EmailVerificationNotificationController:17`, `EmailVerificationPromptController:18`, `RegisteredUserController:49`, `VerifyEmailController:18`/`:25`). They use `route('dashboard', absolute: false)`, so grep for `route('dashboard')` misses them. If you touch that flow, redirect by role to `owner.dashboard`/`kasir.dashboard`.
- **Registration is dead**: `RegisteredUserController` creates `name`, and `resources/views/auth/register.blade.php` does not exist.
- DB state re-verified: 26/26 migrations ran, `hideout_coffe` has 21 tables, users `mhdhsbyy`/`zein`/`daniel` exist. `docs/erd.md`'s header warning that only 5/19 ran is **stale**. Re-query row counts instead of trusting numbers. `docs/` holds tracked `class-diagram.md` + `erd.md`; `db:show`'s table count spans all schemas (~50), not just this DB.
- **MySQL is shared with unrelated apps** (`binaku`, `minimarket_jayusman`) and ships with Laragon, not as a Windows service. Start Laragon/mysqld first; never point `DB_DATABASE` at a sibling and never `migrate:fresh` while another app runs.
- `.env.example` ships SQLite + `APP_LOCALE=en`; `.env` uses MySQL `hideout_coffe` + `APP_LOCALE=id`. Fix the example before a fresh install.
- **Tailwind is v3 only.** `postcss.config.js` uses `tailwindcss` + `autoprefixer`; `@tailwindcss/vite` (v4) is installed but not wired. Do not use v4 syntax.
- Never re-add the `storage/framework/views/*.php` glob to `tailwind.config.js` — an empty directory after `view:clear` kills the CSS build with `ENOENT`. If styles vanish, run `php artisan view:cache` or restart Vite.
- Seeder order matters: `UserSeeder` first (creates roles, deletes stale `hasby`), then categories/menus/promos/transactions. `TransactionSeeder` deletes every transaction before reseeding.
- No CI (no `.github/`).
- `composer dev` is slow on Windows without OPcache; that is PHP recompiling, not a slow query. Enabling OPcache drops warm TTFB sharply.
- Check free disk space before debugging "mysterious" build errors. A full disk has corrupted `vendor/bin/pint` before (`phar error: internal corruption`); free space and `composer reinstall --dev laravel/pint`.
