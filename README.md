# Finexa — Digital Banking System

A full-stack Laravel 12 + MySQL banking system following strict MVC architecture.

## Project Folder Order (For Demo)

Use this order when presenting the project structure.

### 1) Run-Critical Dependencies and Configuration (Top)

```
vendor/                # Laravel framework and Composer packages
bootstrap/             # Laravel bootstrapping
config/                # App/database/session/mail configuration
.env                   # Environment values (DB, app key, etc.)
routes/                # Route definitions (web/console)
public/                # Web entry point and public assets
storage/               # Runtime cache/log/session folders
Database/              # SQL schema backup/import file
artisan                # Laravel CLI entry point
composer.json          # Dependency manifest
composer.lock          # Locked dependency versions
```

### 2) MVC Application Layer (Bottom)

```
Controllers/           # Controllers
Models/                # Models
resources/views/       # Views (Blade templates)
Notifications/         # App notification classes used by controllers/models
app/Http/Middleware/   # Access and role middleware
```

Note: Laravel requires these folders to stay in their expected paths. This section is a presentation order, not a folder relocation.

## 🔧 Tech Stack

| Layer | Technology |
|---|---|
| Backend | PHP 8.2 / Laravel 12 |
| Database | MySQL (InnoDB, ACID-compliant) |
| Frontend | Bootstrap 5 (CDN) + Blade Templates |
| Auth | Laravel Session Auth + Hardcoded Admin |
| Server | XAMPP (php artisan serve) |

---

## 🏗️ Architecture (Strict MVC)

```
app/
├── Http/
│   ├── Controllers/      ← 7 feature controllers + base
│   │   ├── Controller.php
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── AccountController.php
│   │   ├── TransactionController.php
│   │   ├── ServiceController.php
│   │   ├── RewardController.php
│   │   └── SupportController.php
│   └── Middleware/
│       └── AdminMiddleware.php
├── Models/               ← 17 Eloquent models
│   └── User, Account, Transaction, Card, Loan, LoanProduct,
│       LoanRepayment, SavingsPlan, Payment, BillCategory,
│       Reward, RewardTier, RewardTransaction,
│       SupportTicket, TicketReply, Notification, UserAddress
database/
└── finexa_schema.sql     ← 18-table MySQL schema
resources/views/          ← Blade views only (no logic)
routes/web.php            ← All route definitions
```

---

## 🚀 Local Setup

### Prerequisites
- XAMPP running (MySQL on port 3306)
- PHP 8.2+ at `C:\xampp\php\`

### 1. Import Database
```bash
# In XAMPP MySQL console or phpMyAdmin
CREATE DATABASE finexa_db_new;
# Then import: database/finexa_schema.sql
```

### 2. Configure Environment
```bash
# .env is pre-configured for local XAMPP
# Verify these lines in .env:
DB_HOST=127.0.0.1
DB_DATABASE=finexa_db_new
DB_USERNAME=root
DB_PASSWORD=
```

### 3. Start the Server
```bash
C:\xampp\php\php.exe artisan serve --port=8000
```

### 4. Open Browser
```
http://127.0.0.1:8000
```

---

## 🔐 Login Credentials

| Role | Username | Password |
|---|---|---|
| Admin | `admin` | `admin123` |
| Customer | Register at `/register` | (your password) |

---

## ✅ Features (20 Core Banking Features)

### Customer Portal
| Feature | Route |
|---|---|
| Register / Login / Logout | `/register`, `/login` |
| Dashboard with stats | `/dashboard` |
| View & Open Accounts | `/accounts` |
| Account Details + Deposit | `/accounts/{id}` |
| Close Account Request | POST `/accounts/{id}/close-request` |
| Update Profile & Password | `/profile` |
| Transfer Money (ACID) | POST `/transactions/transfer` |
| Deposit Money | POST `/transactions/deposit` |
| Withdraw Money | POST `/transactions/withdraw` |
| Transaction History | `/transactions` |
| Request Debit/Credit Card | POST `/services/cards/request` |
| Freeze / Unfreeze Card | POST `/services/cards/{id}/freeze` |
| Apply for Loan | POST `/services/loans/apply` |
| Track Loan Status | `/services` → Loans tab |
| Open DPS / FDR | POST `/services/savings` |
| Pay Utility Bill | POST `/services/payments/bill` |
| Mobile Recharge | POST `/services/payments/recharge` |
| Earn Reward Points (Auto) | Triggered on transfer/deposit |
| Redeem Points | POST `/rewards/redeem` |
| Create Support Ticket | POST `/support` |
| Reply to Ticket | POST `/support/{id}/reply` |

### Admin Portal (`/admin/*`)
| Feature | Route |
|---|---|
| System Dashboard | `/admin/dashboard` |
| Approve/Reject Loans | `/admin/loans` |
| View All Tickets | `/admin/support` |

---

## 📐 MVC Rules Followed

- ✅ Controllers handle HTTP logic only — no HTML
- ✅ Models contain all business logic, relationships, helpers
- ✅ Views contain only Blade rendering — no PHP business logic
- ✅ Routes protected by `auth` and `admin` middleware
- ✅ ACID-compliant transactions via `DB::transaction()`
- ✅ Validation in every controller method
- ✅ Flash messages for success/error feedback
- ✅ Maximum 7 feature controllers maintained
