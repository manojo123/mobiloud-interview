FROM laravelsail/php83-composer

# Cria pasta de trabalho
WORKDIR /var/www/html

# Copia tudo
COPY . .

# Instala dependências
RUN composer install --no-interaction --prefer-dist --optimize-autoloader
RUN php artisan migrate --seed --force

# Gera chave se precisar (opcional)
RUN php artisan key:generate

# Expõe porta padrão do Railway
EXPOSE 8000

# Starta o servidor embutido escutando na porta que Railway define
CMD php artisan serve --host=0.0.0.0 --port=${PORT:-8000}
