# Inventory Management System — Backend API

A REST API built with **Laravel** and **Laravel Sanctum** for authentication, serving the Inventory Management System frontend.

---

## 📋 Table of Contents

- [Software Requirements](#software-requirements)
- [Getting Started](#getting-started)
- [Running the Server](#running-the-server)
- [API Endpoints](#api-endpoints)
- [Troubleshooting](#troubleshooting)

---

## 🖥️ Software Requirements

| Software | Version | Download |
|----------|---------|----------|
| PHP | >= 8.1 | https://www.php.net/downloads |
| Composer | Latest | https://getcomposer.org/download |
| MySQL | >= 8.0 | https://dev.mysql.com/downloads |
| Git | Latest | https://git-scm.com/downloads |

> **Windows users:** It is recommended to use [XAMPP](https://www.apachefriends.org/) or [Laragon](https://laragon.org/) for PHP and MySQL.

---

## 🚀 Getting Started

### 1. Clone the repository

```bash
git clone <(https://github.com/NimayKa/inventory-management-be)> inventory-backend
cd inventory-backend
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Create the environment file

```bash
cp .env.example .env
```

### 4. Configure the `.env` file

Open `.env` in a text editor and update the following values:

```env
APP_NAME="Inventory Management System"
APP_URL=http://localhost:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=inventory_db
DB_USERNAME=root
DB_PASSWORD=your_mysql_password
```

> Replace `your_mysql_password` with your actual MySQL root password. If no password is set, leave it blank.

### 5. Create the MySQL database

Log in to MySQL and create the database:

```bash
mysql -u root -p
```

```sql
CREATE DATABASE inventory_db;
EXIT;
```

### 6. Generate the application key

```bash
php artisan key:generate
```

### 7. Run database migrations and seeders

```bash
php artisan migrate --seed
```

### 8. Install and publish Laravel Sanctum

> Skip this step if Sanctum is already configured in the project.

```bash
php artisan vendor:publish --provider="Laravel\Sanctum\SanctumServiceProvider"
php artisan migrate
```

### 9. Set storage permissions *(Linux/macOS only)*

```bash
chmod -R 775 storage bootstrap/cache
```

---

## ▶️ Running the Server

```bash
php artisan serve
```

The API will be available at: **http://localhost:8000**

> Make sure MySQL is running before starting the server.

---

## 🔑 Default Credentials

After running the database seeder, you can log in with:

| Field | Value |
|-------|-------|
| username | `admin` |
| Password | `changeme123` |

> If no seeder is set up, register a new account via `POST /api/register`.

---

## 📡 API Endpoints

All routes are prefixed with `/api`. Protected routes require a Sanctum token passed as a Bearer token in the `Authorization` header.

### Authentication

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| POST | `/api/register` | Register a new user | No |
| POST | `/api/login` | Login and receive token | No |
| POST | `/api/logout` | Logout and revoke token | Yes |

### Inventory Items

| Method | Endpoint | Description | Auth Required |
|--------|----------|-------------|---------------|
| GET | `/api/items` | Get all inventory items | Yes |
| POST | `/api/items` | Create a new item | Yes |
| GET | `/api/items/{id}` | Get a specific item | Yes |
| PUT | `/api/items/{id}` | Update an item | Yes |
| DELETE | `/api/items/{id}` | Delete an item | Yes |

---

## 🛠️ Troubleshooting

**CORS errors from the frontend**
Ensure the frontend URL (`http://localhost:5173`) is listed in `config/cors.php` under `allowed_origins`.

**Port already in use**
Run on a different port and inform the frontend team:
```bash
php artisan serve --port=8001
```

**MySQL connection refused**
Ensure your MySQL service is running.
- Windows (XAMPP): Start MySQL from the control panel
- Linux/macOS: `sudo service mysql start`
