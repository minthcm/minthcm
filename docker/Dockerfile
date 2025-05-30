FROM ubuntu:24.04

LABEL maintainer="docker@minthcm.com"
LABEL version="0.2"
LABEL description="Minthcm Docker image"

ENV DEBIAN_FRONTEND=noninteractive

#Basics
RUN apt update && \
apt install -y software-properties-common git curl cron sudo

#apache2 web server
RUN apt update && apt install -y apache2 && a2enmod rewrite && a2enmod headers

#php8.2
RUN add-apt-repository ppa:ondrej/php && \
apt update && \
apt install -y php8.2 php8.2-curl php8.2-zip php8.2-gd php8.2-imap php8.2-mysql php8.2-xml

#php8.2 and apache2 config
COPY docker/config/php-minthcm.ini /etc/php/8.2/mods-available/php-minthcm.ini
RUN ln -s /etc/php/8.2/mods-available/php-minthcm.ini /etc/php/8.2/cli/conf.d/20-minthcm.ini 
RUN ln -s /etc/php/8.2/mods-available/php-minthcm.ini /etc/php/8.2/apache2/conf.d/20-minthcm.ini 
COPY docker/config/000-default.conf /etc/apache2/sites-available/000-default.conf

COPY docker/script/generate_config.php /var/www/script/

#RUN SERVICES
COPY docker/script/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh
CMD bash -C '/usr/local/bin/start.sh';'bash'

EXPOSE 80

WORKDIR /var/www/MintHCM
