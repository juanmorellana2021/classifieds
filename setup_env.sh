#!/bin/bash
cd /var/www/html/classifiedsperu

# Update .env for PostgreSQL
sed -i 's/DB_CONNECTION=sqlite/DB_CONNECTION=pgsql/' .env
sed -i '/DB_CONNECTION=pgsql/a DB_HOST=127.0.0.1\nDB_PORT=5432\nDB_DATABASE=classifiedsperu\nDB_USERNAME=classifiedsuser\nDB_PASSWORD=ClassPeru2026!' .env

# Remove old sqlite lines if present
sed -i '/^# DB_HOST/d' .env
sed -i '/^# DB_PORT/d' .env
sed -i '/^# DB_DATABASE/d' .env
sed -i '/^# DB_USERNAME/d' .env
sed -i '/^# DB_PASSWORD/d' .env

# Set APP_URL and APP_ENV
sed -i 's|APP_URL=http://localhost|APP_URL=http://108.175.12.152/classifiedsperu|' .env
sed -i 's/APP_ENV=local/APP_ENV=production/' .env
sed -i 's/APP_DEBUG=true/APP_DEBUG=false/' .env

echo "=== DB section of .env ==="
grep -A6 "DB_CONNECTION" .env | head -10
