# 1. IMAGEM BASE: Usa a imagem oficial do PHP com Apache e versão 8.2
FROM php:8.2-apache

# 2. VARIÁVEIS DE AMBIENTE:
#    Define a pasta 'View' como a raiz de documentos do Apache (DocumentRoot)
ENV APACHE_DOCUMENT_ROOT /var/www/html/View

# 3. INSTALAÇÃO DE DEPENDÊNCIAS PHP COMUNS:
#    Instala extensões essenciais (você pode ajustar estas conforme a necessidade)
RUN apt-get update && apt-get install -y \
    libpq-dev \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    git \
    unzip \
    && rm -rf /var/lib/apt/lists/*

# 4. HABILITA EXTENSÕES PHP:
RUN docker-php-ext-install pdo_mysql pdo_pgsql gd zip

# 5. CONFIGURAÇÃO DO APACHE (CRÍTICA):
#    Cria a configuração padrão apontando para a pasta View
RUN echo "<VirtualHost *:80>\n" \
    "    DocumentRoot ${APACHE_DOCUMENT_ROOT}\n" \
    "    <Directory ${APACHE_DOCUMENT_ROOT}>\n" \
    "        Options Indexes FollowSymLinks\n" \
    "        AllowOverride All\n" \
    "        Require all granted\n" \
    "    </Directory>\n" \
    "    ErrorLog \${APACHE_LOG_DIR}/error.log\n" \
    "    CustomLog \${APACHE_LOG_DIR}/access.log combined\n" \
    "</VirtualHost>" > /etc/apache2/sites-available/000-default.conf

# 6. HABILITA A CONFIGURAÇÃO DE REWRITE DE URL:
RUN a2enmod rewrite

# 7. COPIA O CÓDIGO:
#    Copia todo o conteúdo da raiz (incluindo o 'Dockerfile', o 'index.php' dentro de 'View', etc.)
COPY . /var/www/html

# 8. PERMISSÕES:
#    Garante que o Apache (www-data) tenha permissão para ler/executar o código.
RUN chown -R www-data:www-data /var/www/html

# 9. EXPOSIÇÃO DE PORTA:
EXPOSE 80
