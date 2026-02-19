# SOJT-web-app

Local development setup for the internship environment task.

## Required Tools (per instructions)
- IDE: Cursor or VS Code
- Laravel: Laravel Herd
- Database access: TablePlus (client)
- MySQL: Provided via Docker (this repo includes a docker-compose setup for MySQL)

---

## 1) Clone the Repository
```bash
git clone <REPO_URL>
cd sojtapp
```

---

## 2) Install PHP Dependencies (Laravel)
```bash
composer install
```

---

## 3) Environment Setup
```bash
cp .env.example .env
php artisan key:generate
```

---

## 4) Start the Database (Docker)
This project uses Docker Compose to run MySQL locally.
### Start containers
```bash
docker compose up -d --build
```
### Check if MySQL is healthy
```bash
docker compose ps
```
### MySQL connection details (for TablePlus)
- `Host`: 127.0.0.1
- `Port`: 3306
- `Database`: Sojt-webapp
- `Username`: laravel
- `Password`: secret

---

## 5) Configure Laravel DB Connection
In .env, set:
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=Sojt-webapp
DB_USERNAME=laravel
DB_PASSWORD=secret
```

---

## 6) Run Migrations
```bash
php artisan migrate
```

---

## 7) Run the App (Laravel Herd)
- Open Laravel Herd
- Ensure this project folder is inside Herd’s watched directory (e.g. C:\Users\AA\Herd\sojtapp)
- Open the Herd site URL (e.g. http://sojtapp.test)
