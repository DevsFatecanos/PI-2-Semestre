# Usa uma imagem base que já tem PHP e Apache
FROM php:8.2-apache 

# Define o diretório de trabalho dentro do container
WORKDIR /var/www/html

# Copia todos os seus arquivos PHP para o diretório do servidor web
COPY . /var/www/html

# O Apache já está configurado para expor na porta 80.
# O Render cuida de redirecionar o tráfego externo para esta porta.
EXPOSE 80
