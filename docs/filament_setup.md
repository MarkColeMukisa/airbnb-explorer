# Filament Setup & Troubleshooting

This document tracks the installation of Filament v4 in the Airbnb Explorer project, including resolutions for common "alarming" hurdles encountered during the process.

## 1. Installation Overview

### Core Commands
The installation was completed using:
```bash
composer require filament/filament:"^4.0"
php artisan filament:install --panels --no-interaction
```

### Key Files Created
- `app/Providers/Filament/AdminPanelProvider.php`: Main configuration for the admin dashboard.
- `bootstrap/providers.php`: Updated to register the `AdminPanelProvider`.

## 2. Troubleshooting "Alarming" Issues

### Issue A: Strict Version Constraint
**Symptoms:** Composer may fail or get stuck on a single version if the constraint is too tight (e.g., `"filament/filament": "4.0"`).
**Fix:** Ensure `composer.json` uses the caret operator (`^`) to allow security and patch updates:
```json
"filament/filament": "^4.0"
```

### Issue B: MongoDB TLS/Handshake Error
**Symptoms:** `ConnectionTimeoutException` or `TLS handshake failed` in browser or terminal.
**Cause:** MongoDB Atlas is rejecting your connection.
**Primary Fix:** 
1. Log in to [MongoDB Atlas](https://cloud.mongodb.com/).
2. Navigate to **Network Access**.
3. Add your current IP address (or `0.0.0.0/0` for testing).

### Issue C: Browser Crash (Sessions & Cache)
**Symptoms:** The entire site fails with a MongoDB error immediately on load.
**Cause:** Your `SESSION_DRIVER` or `CACHE_STORE` is set to `database`, and it's trying to use MongoDB to start the app. 
**Immediate Workaround:**
Change these in your `.env`:
```dotenv
SESSION_DRIVER=file
CACHE_STORE=file
```
This unblocks the site while you fix the database connection.

### Issue C: Stuck at Panel ID Prompt
**Symptoms:** The installer asks "What is the panel's ID?" and stops.
**Fix:** Use the `--no-interaction` flag to accept the default `admin` ID automatically.

## 3. Verification

To verify the dashboard is active:
1. Run `php artisan serve`.
2. Visit `http://localhost:8000/admin` in your browser.
3. Check registered routes: `php artisan route:list --path=admin`.
