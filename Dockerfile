FROM php:8.2-fpm

# تثبيت امتدادات PHP اللازمة للارافيل
RUN apt-get update && apt-get install -y \
    git unzip libzip-dev libpng-dev libonig-dev libxml2-dev curl \
    && docker-php-ext-install pdo pdo_mysql zip mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:2.6 /usr/bin/composer /usr/bin/composer

# ضبط مجلد العمل
WORKDIR /var/www

# نسخ ملفات المشروع
COPY . .

# تثبيت حزم المشروع
RUN composer install --no-dev --optimize-autoloader

# إعداد الأذونات
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# تشغيل السيرفر الداخلي للـ PHP
CMD php artisan serve --host=0.0.0.0 --port=8000
