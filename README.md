# TableFast 🍽️

A restaurant table reservation system built with **Laravel 11**, **Livewire 3**, and **Alpine.js**.

Customers reserve a table through a 3-step inline wizard (register → details → confirm). Staff manage incoming reservations through a PIN-protected admin panel. The UI is bilingual (🇨🇿 Czech / 🇬🇧 English).

---

## Features

### Customer side
- **3-step reservation wizard** — registration, table details, and confirmation in one seamless flow
- Smart date picker — defaults to today if slots are still available, otherwise tomorrow
- 30-minute time slots filtered by opening hours, kitchen close time, and minimum advance notice
- Real-time capacity check — user is notified instantly if no tables are available
- Zone selection (Main Hall, Garden, Bar) with per-zone food and time restrictions
- Optional *"Reserve for someone else"* field with a guest name
- Confirmation email sent immediately after submission (captured by Mailpit in development)
- **My Reservations** dashboard (`/dashboard`) — list with status badges and two-step cancellation

### Admin side *(PIN-protected at `/adm`)*
- Live dashboard — today's pending reservations with countdown timer and auto-refresh every 60 s
- Confirm / Reject reservations; assign tables; create and edit walk-in reservations
- Zone management — open/close zones, food rules, zone-specific hours, default zone
- Table management — CRUD with capacity, active toggle, and custom availability windows
- Restaurant settings — opening hours per weekday, kitchen close time, max guests, confirmation timeout, advance notice rules

---

## Tech stack

| Layer | Technology |
|---|---|
| Backend | Laravel 11, PHP 8.2+ |
| Frontend | Livewire 3, Alpine.js, Tailwind CSS |
| Database | MySQL 8 |
| Authentication | Laravel Breeze (Livewire stack) |
| Tests | Pest PHP |
| Dev email | Mailpit (via Docker) |

---

## Requirements

- [Docker Desktop](https://www.docker.com/products/docker-desktop/) (includes Docker Compose)
- Git

That's it — PHP, Composer, Node.js, and MySQL all run inside Docker via **Laravel Sail**.

---

## Quick start

### 1. Clone the repository

```bash
git clone https://github.com/PN99/TableFast.git
cd TableFast
```

### 2. Copy the environment file

```bash
cp .env.example .env
```

> The `.env.example` is pre-configured for Sail — no changes are needed for a standard setup.

### 3. Install PHP dependencies

> This step requires PHP and Composer locally **only to bootstrap Sail**. Alternatively, Docker can do it without a local PHP install — see [Laravel docs](https://laravel.com/docs/sail#installing-composer-dependencies-for-existing-projects).

```bash
composer install --ignore-platform-reqs
```

### 4. Start Sail (builds containers on first run)

```bash
./vendor/bin/sail up -d
```

On **Windows** use:
```bash
vendor\laravel\sail\bin\sail up -d
```

This starts three containers:

| Container | Purpose | URL / Port |
|---|---|---|
| `laravel.test` | PHP 8.4 app server | http://localhost:**8000** |
| `mysql` | MySQL 8.4 database | `127.0.0.1:3306` |
| `mailpit` | Email catcher | http://localhost:**8025** |

### 5. Generate app key & run migrations with seed data

```bash
./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate --seed
```

### 6. Build frontend assets

```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

### 7. Open the application

**→ http://localhost:8000**

---

## Email (Mailpit)

All outgoing emails (reservation confirmations, password resets) are captured by **Mailpit** — nothing is delivered to real inboxes.

**→ http://localhost:8025**

---

## Demo accounts

All customer accounts have the password **`password`**.

### Admin — PIN pad at `/adm`

| URL | PIN |
|---|---|
| http://localhost:8000/adm | `222555` |

### Customer accounts

| Name | Email |
|---|---|
| Jan Novák | jan@example.com |
| Marie Horáčková | marie@example.com |
| Tomáš Procházka | tomas@example.com |

The seed data includes 16 pre-built reservations spread across today, tomorrow, and the past — the admin dashboard looks populated immediately after setup.

---

## Running tests

```bash
./vendor/bin/sail artisan test
```

Tests run against an **in-memory SQLite** database — no extra configuration needed.

```
Tests:  112 passed (282 assertions)
```

**Coverage includes:**
- Reservation wizard — registration, validation, capacity checks, guest name, food/kitchen rules
- User dashboard — own reservations only, cancellation rules
- Admin dashboard — confirm, reject, walk-in creation, race condition detection, edit & table reassignment
- Zone & table manager — full CRUD, validation, deletion guards
- Admin PIN pad — authentication, rate limiting (5 attempts)
- Auth flows — login, registration, password reset, email verification

---

## Useful Sail commands

```bash
# Stop all containers
./vendor/bin/sail down

# Stop and remove volumes (wipes the database)
./vendor/bin/sail down -v

# Re-seed the database from scratch
./vendor/bin/sail artisan migrate:fresh --seed

# Open a shell inside the app container
./vendor/bin/sail shell

# Run a single test file
./vendor/bin/sail artisan test --filter ReservationWizardTest
```

---

## Application routes

| Route | Description | Access |
|---|---|---|
| `/` | Homepage + reservation wizard | Public |
| `/rezervace` | Reservation wizard (direct link) | Public |
| `/login` | Login | Guest |
| `/register` | Registration | Guest |
| `/dashboard` | My Reservations | Auth |
| `/profile` | Account settings | Auth |
| `/adm` | Admin PIN login | Public |
| `/admin/dashboard` | Reservation management | Admin |
| `/admin/zones` | Zone management | Admin |
| `/admin/zones/{zone}/tables` | Table management | Admin |
| `/admin/settings` | Restaurant settings & hours | Admin |
| `/locale/{cs\|en}` | Language switcher | Public |

---

## Project structure

```
app/
├── Http/Middleware/
│   └── SetLocale.php              # Per-request locale (user DB → session → default)
├── Livewire/
│   ├── ReservationWizard.php      # 3-step customer reservation wizard
│   ├── UserDashboard.php          # Customer reservation list + cancellation
│   ├── AdminPinPad.php            # PIN authentication
│   └── Admin/
│       ├── Dashboard.php          # Reservation management (confirm/reject/edit)
│       ├── ZoneManager.php        # Zone CRUD
│       ├── TableManager.php       # Table CRUD
│       └── RestaurantSettings.php # Settings + per-day opening hours
├── Services/
│   └── ReservationService.php     # Capacity check with lockForUpdate() (race-condition safe)
├── Models/
│   ├── Reservation.php            # Scopes: active(), overlapping(Carbon, Carbon)
│   ├── Zone.php
│   └── Table.php
└── Mail/
    └── ReservationConfirmationMail.php

database/
├── seeders/
│   ├── DatabaseSeeder.php         # Orchestrator
│   ├── AdminUserSeeder.php
│   ├── RestaurantSeeder.php
│   ├── ZoneTableSeeder.php
│   └── ReservationSeeder.php      # Dynamic dates — always relative to today
└── migrations/                    # 18 versioned migrations

lang/
├── cs/ui.php                      # Czech UI strings
└── en/ui.php                      # English UI strings

tests/
├── Unit/
│   ├── Models/ReservationTest.php
│   ├── Models/ZoneTest.php
│   └── Services/ReservationServiceTest.php
└── Feature/
    ├── ReservationWizardTest.php
    ├── UserDashboardTest.php
    ├── AdminPinPadTest.php
    ├── Admin/DashboardTest.php
    ├── Admin/ZoneManagerTest.php
    ├── Admin/TableManagerTest.php
    └── Auth/...
```

---

## License

MIT
