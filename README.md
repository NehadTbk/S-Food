# S-Food

A Laravel web application for a home cook offering dishes for pickup or delivery. Customers can order, track their orders, and maintain a public profile. Admins manage the menu, news, FAQ, orders, users, and contact messages. Delivery drivers manage their deliveries.

Built as a Laravel re-exam project — with the underlying idea that this project could genuinely be used if my mother ever decides to start her own business.

## Features by user type

The application has 4 user types: guests/visitors, customers, admins, and deliverers.

### Guest / visitor (not logged in)

- Browse the home page and menu
- View any public profile page (`/profiel/{username}`)
- View the news overview and detail pages
- View the FAQ
- Submit the contact form
- Register for an account or log in

### Customer (role: user)

Everything a guest can do, plus:

- Edit their own public profile
- Add dishes to the shopping cart and check out
- View "my orders" and order detail, cancel an order

### Admin

- Manage menu items, categories, and daily specials
- Manage news items (add/edit/delete)
- Manage FAQ categories and questions (add/edit/delete)
- Manage orders: confirm, cancel, mark as paid (cash), generate a QR payment code
- Manage users: create manually, change roles, activate/deactivate accounts
- View all contact messages and reply to them by email

### Deliverer

- View available (unclaimed) deliveries
- Accept a delivery
- View "my deliveries"
- Mark a delivery as paid (cash) or generate a QR payment code

## Installation

Requirements: PHP 8.2+, Composer, Node.js + npm, MySQL.

```bash
git clone <repo-url>
cd S-Food

composer install
npm install

cp .env.example .env
php artisan key:generate
```

Create a MySQL database matching your `.env` (`DB_DATABASE`, default `s_food`), then adjust `DB_USERNAME`/`DB_PASSWORD` in `.env` if needed:

```bash
mysql -u root -p -e "CREATE DATABASE s_food"
```

(Drop `-p` if your local MySQL root user has no password.)

```bash
php artisan migrate --seed
php artisan storage:link

npm run build
php artisan serve
```

The site is then available at `http://localhost:8000`.

For front-end changes during development: `npm run dev` (Vite watcher) in a separate terminal, or `composer run dev` to start the server, queue listener, logs, and Vite watcher together.

### Default admin account

After seeding (`php artisan migrate --seed`):

- **Email:** admin@ehb.be
- **Password:** Password!321

### Email

`MAIL_MAILER` defaults to `log`, so sent emails (contact form, replies to contact messages) end up in `storage/logs/laravel.log` instead of actually being sent. Update `.env` with real SMTP credentials to send emails for real.

## Tech stack

- [Laravel 12](https://laravel.com/docs)
- [Laravel Breeze](https://github.com/laravel/breeze) — authentication scaffolding (login/register/password reset)
- [Tailwind CSS](https://tailwindcss.com/docs)
- [Alpine.js](https://alpinejs.dev/) — front-end interactivity (dropdowns, modals, toggles)
- MySQL (default) — any Laravel-supported database can be used via `.env`

## Source attribution

This project was built using the official documentation of Laravel, Laravel Breeze, Tailwind CSS, and Alpine.js as the primary reference. No external tutorials or third-party code snippets were used.
