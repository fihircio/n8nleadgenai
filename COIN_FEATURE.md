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
- View all users' coin balances in the user table.
- Edit a user's coin balance directly from the edit user page.
- Set an initial coin balance when creating a user.

---

## User UI
- Users see their coin balance on their profile page.
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

For questions or contributions, see the main `README.md` or contact the project maintainer.
