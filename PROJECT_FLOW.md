# Project Documentation: SaaShovel SaaS Starter Kit

## Table of Contents
- [Project Overview](#project-overview)
- [Tech Stack](#tech-stack)
- [Architecture & Flow Chart](#architecture--flow-chart)
- [Route & Controller Map](#route--controller-map)
- [Custom Middleware](#custom-middleware)
- [Admin Panel](#admin-panel)
- [Billing & Subscription Flow](#billing--subscription-flow)
- [Coin System](#coin-system)
- [Notifications](#notifications)
- [Extension Points](#extension-points)
- [Setup & Getting Started](#setup--getting-started)
- [Contributing & Coding Standards](#contributing--coding-standards)
- [FAQ](#faq)

---

## Project Overview
SaaShovel is a TALL stack SaaS starter kit with multi-provider billing, a virtual coin system, permission-based content, and a modern admin panel. It is designed for rapid SaaS prototyping and production.

## Tech Stack
- **Backend:** Laravel, Livewire
- **Frontend:** Tailwind CSS, Alpine.js
- **Admin:** Filament
- **Payments:** Stripe, LemonSqueezy, Paddle, NOWPayments (crypto)
- **Auth:** Jetstream, Fortify, Socialite
- **Other:** Spatie Permission, Cookie Consent, Google Analytics, etc.

## Architecture & Flow Chart
```
[User] → [Routes] → [Middleware] → [Controllers/Livewire] → [Models] → [Database]
   ↓             ↓
[Admin Panel] [Notifications]
   ↓
[Billing Providers]
```

### Key Flows
- **User triggers automation:**
  1. POST `/api/automation/trigger` → `AutomationController@trigger`
  2. Deduct coins, dispatch job, n8n workflow, result callback
  3. POST `/automation/result` (API) → store result, notify user
- **Premium content access:**
  1. GET `/premium/silver` (etc.) → `coins:X` middleware
  2. Deduct coins, show content
- **Subscription management:**
  1. User subscribes via dashboard (Livewire)
  2. Billing provider handles payment, webhook updates user/roles

## Route & Controller Map
| Route                        | Method | Middleware                | Controller/Handler                | Purpose                       |
|------------------------------|--------|---------------------------|-----------------------------------|-------------------------------|
| `/`                          | GET    | web                       | Home (Livewire)                   | Landing/Home page             |
| `/dashboard`                 | GET    | auth                      | Dashboard (Livewire)              | User dashboard                |
| `/coins/history`             | GET    | auth                      | Blade view                        | Coin history page             |
| `/api/coins/balance`         | GET    | auth:sanctum              | CoinController@balance            | Get coin balance (API)        |
| `/api/coins/deposit`         | POST   | auth:sanctum              | CoinController@deposit            | Deposit coins (API)           |
| `/api/coins/withdraw`        | POST   | auth:sanctum              | CoinController@withdraw           | Withdraw coins (API)          |
| `/api/automation/trigger`    | POST   | auth:sanctum              | AutomationController@trigger      | Trigger automation (API)      |
| `/automation/result`         | POST   | -                         | Closure (routes/coin_result_api)  | Receive automation result      |
| `/premium/silver`            | GET    | auth, coins:10            | Closure (routes/web.php)          | Silver premium content         |
| `/premium/gold`              | GET    | auth, coins:50            | Closure (routes/web.php)          | Gold premium content           |
| `/premium/platinum`          | GET    | auth, coins:100           | Closure (routes/web.php)          | Platinum premium content       |
| `/billing`, `/billing-ls`, etc.| GET/POST| auth, IsAjaxRequest      | Closure (routes/web.php)          | Billing portals                |
| `/admin`                     | GET    | filament, auth            | Filament AdminPanelProvider       | Admin panel                    |

## Custom Middleware
- `coins:X` — Ensures user has at least X coins, deducts on access
- `IsAjaxRequest` — Restricts route to AJAX requests
- `EnsureUserIsSubscribed`, `EnsureUserIsSubscribedToFirstTier`, etc. — Restrict by subscription tier/role

## Admin Panel
- Built with Filament (`/admin`)
- Features: user management, coin management, content (posts, library, tags), analytics, environment editor, impersonation
- Custom widgets: CoinStats, CoinFlowChart, Google Analytics (optional)

## Billing & Subscription Flow
- Supports Stripe, LemonSqueezy, Paddle, NOWPayments (crypto)
- User selects plan (Livewire dashboard)
- Payment handled by provider, webhooks update user/roles
- Subscription status polled via `/pollStatus/{userId}`
- Users can manage/cancel/restore subscriptions from dashboard

## Coin System
- Virtual coins for feature gating and premium content
- API endpoints for balance, deposit, withdraw
- Coin deduction on premium route access
- Admin can adjust balances via panel
- See `COIN_FEATURE.md` for details

## Notifications
- Users notified of automation results (via Notification system)
- Email and in-app notifications supported

## Extension Points
- **Add new premium route:**
  1. Add route in `routes/web.php` with `coins:X` middleware
  2. Create Blade/Livewire view for content
- **Add new admin resource:**
  1. Create Filament Resource in `app/Filament/Resources/`
  2. Register in `AdminPanelProvider`
- **Add new billing provider:**
  1. Implement provider logic (see `config/saashovel.php`)
  2. Add webhooks, update subscription logic
- **Add new notification:**
  1. Create Notification class in `app/Notifications/`
  2. Trigger from controller or event

## Setup & Getting Started
1. **Clone the repo:**
   ```zsh
   git clone <repo-url>
   cd n8nleadgenai
   ```
2. **Install dependencies:**
   ```zsh
   composer install
   npm install
   ```
3. **Copy and edit `.env`:**
   ```zsh
   cp .env.example .env
   # Edit DB, mail, billing, etc.
   ```
4. **Generate app key:**
   ```zsh
   php artisan key:generate
   ```
5. **Run migrations and seeders:**
   ```zsh
   php artisan migrate --seed
   ```
6. **Build assets:**
   ```zsh
   npm run build
   ```
7. **Start the server:**
   ```zsh
   php artisan serve
   ```
8. **Access the app:**
   - Frontend: http://localhost:8000
   - Admin: http://localhost:8000/admin

## Contributing & Coding Standards
- **PRs:** Fork, branch, PR with clear description
- **Coding style:** PSR-12 for PHP, Prettier for JS/Blade
- **Tests:** Add/maintain feature and unit tests for new features
- **Docs:** Update this file and `COIN_FEATURE.md` for new features
- **Security:** Never commit secrets, use `.env`

## FAQ
- See [COIN_FEATURE.md](COIN_FEATURE.md) and in-app help for more details
- For support, contact the maintainer or open an issue

---

*Generated on 2025-06-15. For the latest, see the project repository.*
