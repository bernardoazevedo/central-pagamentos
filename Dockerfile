FROM php:8.2.5-apache

ARG UID=1000
ARG GID=1000
ARG NODE_VERSION=22.12

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


# configurando grupo e usuário
RUN if getent group ${GID}; then \
      group_name=$(getent group ${GID} | cut -d: -f1); \
      useradd -m -u ${UID} -g ${GID} -s /bin/bash www; \
    else \
      groupadd -g ${GID} www && \
      useradd -m -u ${UID} -g www -s /bin/bash www; \
      group_name=www; \
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

# instalando dependências do código
RUN composer install --no-dev --prefer-dist --no-scripts --no-progress --no-suggest


# instalando nvm
RUN export NVM_DIR="$HOME/.nvm" && \
    curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.40.0/install.sh | bash && \
    [ -s "$NVM_DIR/nvm.sh" ] && . "$NVM_DIR/nvm.sh" && \
    nvm install ${NODE_VERSION} && \
    nvm alias default ${NODE_VERSION} && \
    nvm use default

ENV NODE_PATH $NVM_DIR/v$NODE_VERSION/lib/node_modules
ENV PATH $NVM_DIR/versions/node/v$NODE_VERSION/bin:$PATH

EXPOSE 5173

CMD ["apache2-foreground"]
