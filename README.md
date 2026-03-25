# PMMS

Personal Money Management System built with Laravel, Blade, plain CSS, and lightweight JavaScript.

## Features

- Premium dashboard with balance, income, expense, given/taken money, recent transactions, charts, and reminders
- CRUD modules for income, expenses, categories, given money, taken money, and reminders
- Filters for dates, status, source/category, and people
- Reports page with cash-flow summary, category analytics, Excel-ready CSV export, and print-to-PDF view
- JSON backup export/import plus scheduled `pmms:backup` command
- Responsive Blade UI with attachment support for income and expense records

## Quick Start

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
php artisan storage:link
php artisan serve
```

The app is configured for SQLite by default. The database file is `database/database.sqlite`.

## Main Pages

- `/` dashboard
- `/incomes` income management
- `/expenses` expense management
- `/given-loans` money given to others
- `/taken-loans` borrowed money
- `/categories` category management
- `/reports` reports and analytics
- `/reminders` reminder center
- `/search` cross-module search
- `/backup` export/import backups

## Backups

- Manual export/import is available from the Backup page
- Daily snapshots can be automated with:

```bash
php artisan schedule:work
```

The scheduled command is:

```bash
php artisan pmms:backup
```

## Test

```bash
php artisan test
```
