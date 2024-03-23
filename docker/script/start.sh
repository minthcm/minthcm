#!/bin/bash

FILE=/var/www/html/.initialized

if [ -f "$FILE" ]; then
  # Run services
  service cron start
  service apache2 start
else
  # Download MintHCM
  minthcm_temp=$(mktemp -d)
  git clone https://github.com/minthcm/minthcm.git $minthcm_temp
  cp -R $minthcm_temp/* /var/www/MintHCM/
  rm -r $minthcm_temp
  php /var/www/script/generate_config.php
  chown -R www-data:www-data /var/www/MintHCM
  chmod -R 755 /var/www/MintHCM
 
  # Check if the config_si.php file was generated
  if [[ ! -f /var/www/MintHCM/configMint4 ]]; then
    printf "Error: Failed to generate configMint4 - please check the configuration\n"
    exit 1
  fi

  touch $FILE
  service apache2 start

  # Make the MintHCM installation request
  printf "Starting MintHCM installation...\n"
  su -s /bin/bash -c 'php /var/www/MintHCM/MintCLI install < /var/www/MintHCM/configMint4' www-data

# Check the exit code
  if [[ $? -ne 0 ]]; then
    printf "Error: MintHCM installation failed - please check logs\n"
  else
    printf "MintHCM installation completed!\n"
    #add cron and start service
    printf "*    *    *    *    *     cd /var/www/MintHCM/legacy; php -f cron.php > /dev/null 2>&1" > /var/spool/cron/crontabs/www-data
    service cron start
  fi
fi
