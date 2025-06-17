# Coin Feature Documentation

## Overview
This project implements a virtual coin system using the `bavix/laravel-wallet` package. Users have a coin balance, which can be managed via API, admin panel, and user-facing UI.

---

## API Endpoints
All endpoints require authentication (`auth:sanctum`).

### Get Coin Balance
- **GET** `/api/coins/balance`
- **Response:** `{ "balance": 100 }`

### Deposit Coins
- **POST** `/api/coins/deposit`
- **Body:** `{ "amount": 10 }`
- **Response:** `{ "balance": 110 }`

### Withdraw Coins
- **POST** `/api/coins/withdraw`
- **Body:** `{ "amount": 5 }`
- **Response:** `{ "balance": 105 }`

---

## Admin Panel (Filament)

- View and edit all users' coin balances.
- Manage workflow templates: create, edit, set coin cost, and categorize templates for the marketplace.
- All changes to workflow templates are reflected in the user-facing marketplace in real time.
---

## Workflow Marketplace Integration

- Users spend coins to purchase and run automated workflows from the Workflow Marketplace.
- Each workflow template displays its coin cost and deducts coins automatically upon purchase.
- The marketplace is accessible from the dashboard or via `/marketplace`.
- Templates are managed by admins in the Filament admin panel and can be seeded for initial setup.

---

## User UI

- Users see their coin balance and transaction history on the dashboard.
- The dashboard embeds the Workflow Marketplace, allowing users to browse and purchase workflows using coins.
- Users can refresh their balance without reloading the page.

---

## Extensibility & Security
- All coin operations are protected by authentication.
- Validation prevents negative or invalid amounts.
- For advanced use, consider adding coin transfer, transaction history, or notifications.

---

## Testing
- Feature and unit tests should cover all API endpoints and model logic.
- See `/tests/Feature/CoinApiTest.php` and `/tests/Unit/UserCoinTest.php` (to be implemented).

---

## Maintenance
- Keep wallet package up to date.
- Monitor for security advisories with `composer audit`.

---

## Workflow Template Seeder

- Workflow templates can be seeded for initial setup or testing.
- See `database/seeders/TemplateListingSeeder.php` for examples.
- Seeded templates are available in both the admin panel and the user-facing marketplace.

---

For questions or contributions, see the main `README.md` or contact the project maintainer.
