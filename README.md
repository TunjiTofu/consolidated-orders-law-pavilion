# Consolidated Data Export/Import System

## Introduction
This Laravel application provides efficient export/import functionality for consolidated order data, optimized for large datasets with:
- Chunked processing
- Queue support
- Detailed logging
- Excel file handling

## Prerequisites
- PHP 8.1+
- Laravel 10+
- MySQL 8+
- Redis (for queue)
- Composer 2+
- Laravel Sail (for Docker setup)

## Installation

### 1. Clone Repository
```bash
git clone https://github.com/TunjiTofu/consolidated-orders-law-pavilion.git
cd consolidated-orders-law-pavilion
```

### 2. Install Dependencies
```bash
composer install
```

### 3. Generate Key
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Update the following variables in the .env file
```bash
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=consolidated
DB_USERNAME=root
DB_PASSWORD=

```

### 3. Run Migrations
```bash
php artisan migrate
```

### 4. Start the application
```bash
php artisan serve
```

## Further Improvements:
 - Authorization and Authentication implementation ✅
 - Ability to specify custom download path ✅

## API Documentation
[Click this Link Access the API Documentation](https://app.gitbook.com/o/XXNaAkNtCMRanbfyrTQm/s/ItbDDRlpa0Wz2QwfIG8F/~/changes/gruTgx99ts0O1WV5Af8O/)

