# Χρήση PHP 8.1 με Apache
FROM php:8.1-apache

# Εγκατάσταση MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli

# Ενεργοποίηση Apache mod_rewrite (αν χρειάζεται για .htaccess)
RUN a2enmod rewrite

# Εγκατάσταση Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Αντιγραφή του κώδικα στο container
COPY ./src /var/www/html/

# Δικαιώματα φακέλου
RUN chown -R www-data:www-data /var/www/html
