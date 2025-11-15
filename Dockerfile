# 1. IMAGEM BASE: PHP com Apache
FROM php:8.2-apache

# 2. VARIÁVEIS DE AMBIENTE: DocumentRoot ajustado para 'View'
ENV APACHE_DOCUMENT_ROOT /var/www/html/View

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

# 6. COPIA O CÓDIGO E INSTALA DEPENDÊNCIAS
WORKDIR /var/www/html
COPY . /var/www/html
RUN composer install --no-dev --optimize-autoloader

# 7. CONFIGURAÇÃO DO APACHE (corrige erro 403 e libera assets)
RUN echo "<VirtualHost *:80>\n" \
    "    DocumentRoot ${APACHE_DOCUMENT_ROOT}\n" \
    "    <Directory ${APACHE_DOCUMENT_ROOT}>\n" \
    "        Options Indexes FollowSymLinks\n" \
    "        AllowOverride All\n" \
    "        Require all granted\n" \
    "    </Directory>\n" \
    "    Alias /Assets /var/www/html/Assets\n" \
    "    <Directory /var/www/html/Assets>\n" \
    "        Options Indexes FollowSymLinks\n" \
    "        AllowOverride None\n" \
    "        Require all granted\n" \
    "    </Directory>\n" \
    "    ErrorLog \${APACHE_LOG_DIR}/error.log\n" \
    "    CustomLog \${APACHE_LOG_DIR}/access.log combined\n" \
    "</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 8. HABILITA REWRITE E PERMISSÕES
RUN a2enmod rewrite
RUN chown -R www-data:www-data /var/www/html

# 9. EXPOSIÇÃO DE PORTA
EXPOSE 80
EXPOSE 443