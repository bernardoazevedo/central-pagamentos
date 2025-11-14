FROM php:8.3-apache

ARG UID=1000
ARG GID=1000

# instalando dependências
RUN apt-get update && \
    apt-get install -y \
    git \
    libzip-dev \
    libpng-dev \
    libicu-dev \
    libpq-dev \
    libmagickwand-dev

RUN docker-php-ext-install pdo_mysql zip exif pcntl bcmath gd

RUN pecl install pcov && docker-php-ext-enable pcov

# configurando grupo e usuário
RUN if getent group ${GID}; then \
      group_name=$(getent group ${GID} | cut -d: -f1); \
      useradd -m -u ${UID} -g ${GID} -s /bin/bash www-data; \
    else \
      groupadd -g ${GID} www-data && \
      useradd -m -u ${UID} -g www-data -s /bin/bash www-data; \
      group_name=www-data; \
    fi

WORKDIR /var/www/html

# configurando apache
COPY docker/app/apache.conf /etc/apache2/sites-available/central-pagamentos.conf
RUN a2dissite 000-default.conf
RUN a2enmod rewrite
RUN a2ensite central-pagamentos.conf

# composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# copiando o código para o container
COPY . /var/www/html

RUN chown -R www-data:www-data /var/www/html/storage/
RUN chmod -R 755 /var/www/html/storage/

ENTRYPOINT composer install && apache2-foreground
