#!/bin/sh
set -e

cd /var/www/html

wait_for_db() {
    if [ -z "${DB_HOST}" ]; then
        return 0
    fi
    echo "Waiting for database at ${DB_HOST}:${DB_PORT:-3306}..."
    for i in $(seq 1 60); do
        if php -r "
            try {
                new PDO(
                    'mysql:host=' . getenv('DB_HOST') . ';port=' . (getenv('DB_PORT') ?: '3306'),
                    getenv('DB_USERNAME'),
                    getenv('DB_PASSWORD')
                );
                exit(0);
            } catch (Throwable \$e) {
                exit(1);
            }
        "; then
            echo "Database is ready."
            return 0
        fi
        sleep 2
    done
    echo "Database connection timed out." >&2
    exit 1
}

if [ ! -f .env ] && [ -f .env.example ]; then
    cp .env.example .env
fi

if [ -z "${APP_KEY}" ] || [ "${APP_KEY}" = "base64:" ]; then
    php artisan key:generate --force --no-interaction 2>/dev/null || true
fi

wait_for_db

chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true

php artisan migrate --force --no-interaction

php artisan storage:link --force 2>/dev/null || true

if [ "${APP_ENV}" = "production" ]; then
    php artisan config:cache --no-interaction
    php artisan route:cache --no-interaction
    php artisan view:cache --no-interaction
fi

exec "$@"
