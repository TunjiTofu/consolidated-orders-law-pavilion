# Consolidated Data Export/Import System

## Introduction
This Laravel application provides efficient export/import functionality for consolidated order data, optimized for large datasets with:
- Chunked processing
- Queue support
- Detailed logging
- Excel file handling

## Prerequisites
-Git installed
-PHP ≥ 8.1 (for non-Docker)
-Composer (for non-Docker)
-Docker Desktop (if using Sail)

-Git installed
-For Docker: Docker Desktop
-For Non-Docker:
    -PHP 8.4
    -Composer 2.6+

## Option 1: Docker Setup (Laravel Sail)

### 1. Clone Repository
```bash
git clone https://github.com/TunjiTofu/consolidated-orders-law-pavilion.git
cd consolidated-orders-law-pavilion
```

### 2. Install dependencies using Sail's PHP 8.4 image
```bash
docker run --rm \
    -u "$(id -u):$(id -g)" \
    -v $(pwd):/var/www/html \
    -w /var/www/html \
    laravelsail/php84-composer:latest \
    composer install --ignore-platform-reqs
```

### 3. Start Container
```bash
./vendor/bin/sail up -d
```

### 4. Generate Key
```bash
cp .env.example .env
./vendor/bin/sail artisan key:generate
```

### 5. Database Setup
```bash
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=sail
DB_PASSWORD=password

```

### 6. Run Migrations
```bash
./vendor/bin/sail artisan migrate
```

### 4. Access the Application
[Access the Application](http://localhost)

## Further Improvements:
 - Authorization and Authentication implementation ✅
 - Ability to specify custom download path ✅

## API Documentation
[Click this Link Access the API Documentation](https://app.gitbook.com/o/XXNaAkNtCMRanbfyrTQm/s/ItbDDRlpa0Wz2QwfIG8F/~/changes/gruTgx99ts0O1WV5Af8O/)

