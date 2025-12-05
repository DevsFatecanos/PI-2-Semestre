# 1. IMAGEM BASE: PHP com Apache
FROM php:8.2-apache

# 2. DOCUMENT ROOT CORRETO (raiz do projeto)
ENV APACHE_DOCUMENT_ROOT /var/www/html

# 3. INSTALAÇÃO DE DEPENDÊNCIAS DO SISTEMA
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 4. HABILITA EXTENSÕES PHP
RUN docker-php-ext-install pdo_mysql pdo_pgsql zip gd

# 5. INSTALAÇÃO DO COMPOSER
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. COPIA O CÓDIGO PARA O CONTAINER
WORKDIR /var/www/html
COPY . /var/www/html

# Evita erro caso não tenha dependências PHP
RUN composer install --no-dev --optimize-autoloader || true

# 7. CONFIGURAÇÃO DO APACHE
RUN echo "<VirtualHost *:80>\n" \
    "    DocumentRoot /var/www/html\n" \
    "    <Directory /var/www/html>\n" \
    "        Options Indexes FollowSymLinks\n" \
    "        AllowOverride All\n" \
    "        Require all granted\n" \
    "    </Directory>\n" \
    "    Alias /Assets /var/www/html/Assets\n" \
    "    <Directory /var/www/html/Assets>\n" \
    "        Options Indexes FollowSymLinks\n" \
    "        AllowOverride All\n" \
    "        Require all granted\n" \
    "    </Directory>\n" \
    "    ErrorLog \${APACHE_LOG_DIR}/error.log\n" \
    "    CustomLog \${APACHE_LOG_DIR}/access.log combined\n" \
    "</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 8. HABILITA REWRITE E AJUSTA PERMISSÕES
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

# 9. EXPOSIÇÃO DE PORTAS
EXPOSE 80
EXPOSE 443
