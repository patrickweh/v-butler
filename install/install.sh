#!/bin/bash
# get the absolute path to this script without own filename
self_path=$(dirname $(realpath $0))

apt install git curl -y

# get hostname with a dialog
if [ -f $self_path/domain ];
then
    hostname=$(cat $self_path/domain)
else
    whiptail --title "Enter domain without http(s)://" --inputbox "Enter domain without http(s)://" 8 60 2> $self_path/domain
    hostname=$(cat $self_path/domain)
fi
if [ -z "$hostname" ]
then
  rm $self_path/domain
  reset
  echo "\e[31m hostname is empty\e[0m"
  exit 0
fi

# get git repo with a dialog
if [ -f $self_path/gitrepo ];
then
    gitrepo=$(cat $self_path/gitrepo)
else
    whiptail --title "Enter git repo" --inputbox "Enter git repo" 8 60 2> $self_path/gitrepo
    gitrepo=$(cat $self_path/gitrepo)
    # add fingerprint to known hosts
    ssh-keyscan -t rsa github.com >> ~/.ssh/known_hosts 2> /dev/null

    if [ ! -f ~/.ssh/id_ed25519.pub ]; then
        ssh-keygen -t ed25519 -N "" -f ~/.ssh/id_ed25519
    fi
    reset
    echo "\e[32m Copy the public key to your git repo\e[0m"
    cat ~/.ssh/id_ed25519.pub
    echo "\e[32m Press enter to continue\e[0m"
    read enter
fi
if [ -z "$gitrepo" ]
then
  rm $self_path/gitrepo
  reset
  # echo a error in red
  echo "\e[31m Git repo is empty\e[0m"
  exit 0
fi

  # check if git repo is valid
if git ls-remote $gitrepo &> /dev/null
then
  echo "Git repo is valid"
else
  rm $self_path/gitrepo
  reset
  echo "\e[31m Git repo is not valid\e[0m"
  exit 0
fi

# get database name, username, password with a dialog
if [ -f $self_path/database ];
then
    database=$(cat $self_path/database)
else
    whiptail --title "Enter database name" --inputbox "Enter database name" 8 60 2> $self_path/database
    database=$(cat $self_path/database)
fi
if [ -z "$database" ]
then
  rm $self_path/database
  reset
  echo "\e[31m Database name is empty\e[0m"
  exit 0
fi

if [ -f $self_path/dbuser ];
then
    dbuser=$(cat $self_path/dbuser)
else
    whiptail --title "Enter database username" --inputbox "Enter database username" 8 60 2> $self_path/dbuser
    dbuser=$(cat $self_path/dbuser)
fi
if [ -z "$dbuser" ]
then
  rm $self_path/dbuser
  reset
  echo "\e[31m Database username is empty\e[0m"
  exit 0
fi

if [ -f $self_path/dbpassword ];
then
    dbpassword=$(cat $self_path/dbpassword)
else
    whiptail --title "Enter database password" --inputbox "Enter database password" 8 60 2> $self_path/dbpassword
    dbpassword=$(cat $self_path/dbpassword)
fi
if [ -z "$dbpassword" ]
then
  rm $self_path/dbpassword
  reset
  echo "\e[31m Database password is empty\e[0m"
  exit 0
fi

if [ ! -d /var/www/$hostname ]
then
  git clone $gitrepo /var/www/$hostname
  chown -R www-data:www-data /var/www/$hostname
fi

# Install nginx
if service --status-all | grep -Fq 'nginx'; then
  echo "\e[32m nginx is already installed\e[0m"
else
  sudo DEBIAN_FRONTEND=noninteractive apt install nginx -y
  sudo ufw allow 'Nginx Full'
  sudo mkdir -p /var/www/$hostname
  sudo chown -R www-data:www-data /var/www/$hostname
  sudo chmod -R 755 /var/www/$hostname
  sudo cp $self_path/nginx /etc/nginx/sites-available/$hostname
  sudo unlink /etc/nginx/sites-enabled/default
  sudo sed -i "s/{\$hostname}/$hostname/g" /etc/nginx/sites-available/$hostname
  sudo ln -s /etc/nginx/sites-available/$hostname /etc/nginx/sites-enabled/
  sudo nginx -t
  sudo systemctl restart nginx
fi

# Uninstall apache2 if installed
if service --status-all | grep -Fq 'apache2'; then
  sudo systemctl stop apache2
  sudo systemctl disable apache2
  sudo apt-get remove apache2 -y
  sudo apt-get purge apache2 -y
  sudo apt-get autoremove -y
  sudo rm -rf /etc/apache2
  sudo rm -rf /var/www/html
else
  echo "\e[32m apache2 is not installed\e[0m"
fi


# Install php
if [ ! -f /usr/bin/php ]
then
	sudo DEBIAN_FRONTEND=noninteractive apt install lsb-release ca-certificates apt-transport-https software-properties-common -y
	sudo apt-add-repository ppa:ondrej/php -y
  sudo DEBIAN_FRONTEND=noninteractive apt update
  sudo DEBIAN_FRONTEND=noninteractive apt -y install unzip
  sudo DEBIAN_FRONTEND=noninteractive apt -y install php8.2
  sudo DEBIAN_FRONTEND=noninteractive apt -y install php8.2-fpm php8.2-redis php8.2-bcmath php8.2-xml php8.2-fpm php8.2-mysql php8.2-zip php8.2-intl php8.2-ldap php8.2-gd php8.2-cli php8.2-bz2 php8.2-curl php8.2-mbstring php8.2-soap
  sudo systemctl restart nginx
fi

# Install mariadb
if service --status-all | grep -Fq 'mariadb'; then
  echo "\e[32m MariaDb is already installed\e[0m"
else
sudo DEBIAN_FRONTEND=noninteractive apt install mariadb-server -y
sudo mysql_secure_installation
sudo mysql -u root -p << EOF
CREATE DATABASE $database;
CREATE USER '$dbuser'@'localhost' IDENTIFIED BY '$dbpassword';
GRANT ALL PRIVILEGES ON $database.* TO '$dbuser'@'localhost';
FLUSH PRIVILEGES;
exit
EOF
fi

# Install supervisor
if service --status-all | grep -Fq 'supervisor'; then
  echo "\e[32m Supervisor is already installed\e[0m"
else
    sudo DEBIAN_FRONTEND=noninteractive apt -y install supervisor
    cp $self_path/laravel.conf /etc/supervisor/conf.d/
    sed -i "s/--placeholder--/$hostname/" /etc/supervisor/conf.d/laravel.conf
fi

# Install Redis
if service --status-all | grep -Fq 'redis-server'; then
  echo "\e[32m Redis is already installed\e[0m"
else
  sudo DEBIAN_FRONTEND=noninteractive apt -y install redis-server
  sudo systemctl enable redis-server.service
  sudo systemctl start redis-server.service
  redispassword=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
  sed -i "s/# requirepass foobared/requirepass $redispassword/" /etc/redis/redis.conf
  sed -i 's/supervised no/supervised systemd/' /etc/redis/redis.conf
  sudo systemctl restart redis.service
fi

# Install Meilisearch
if [ ! -f /usr/local/bin/meilisearch ]
then
  curl -L https://install.meilisearch.com | sh
  mv ./meilisearch /usr/local/bin/
  useradd -d /var/lib/meilisearch -b /bin/false -m -r meilisearch
  sudo chmod a+x /usr/local/bin/meilisearch
  curl https://raw.githubusercontent.com/meilisearch/meilisearch/latest/config.toml > /etc/meilisearch.toml
  meilisearchKey=$(cat /dev/urandom | tr -dc 'a-zA-Z0-9' | fold -w 32 | head -n 1)
  sed -i "s/env = \"development\"/env = \"production\"/" /etc/meilisearch.toml
  sed -i "s/# master_key = \"YOUR_MASTER_KEY_VALUE\"/master_key = \"$meilisearchKey\"/" /etc/meilisearch.toml
  sed -i "s/db_path = \"\.\/data\.ms\"/db_path = \"\/var\/lib\/meilisearch\/data\"/" /etc/meilisearch.toml
  sed -i "s/dump_dir = \"dumps\/\"/dump_dir = \"\/var\/lib\/meilisearch\/dumps\"/" /etc/meilisearch.toml
  sed -i "s/snapshot_dir = \"snapshots\/\"/snapshot_dir = \"\/var\/lib\/meilisearch\/snapshots\"/" /etc/meilisearch.toml
  mkdir /var/lib/meilisearch/data /var/lib/meilisearch/dumps /var/lib/meilisearch/snapshots
  chown -R meilisearch:meilisearch /var/lib/meilisearch
  chmod 750 /var/lib/meilisearch
  cp $self_path/meilisearch.service /etc/systemd/system/
  systemctl enable meilisearch
  systemctl start meilisearch
  systemctl status meilisearch
else
  # echo in green that meilisearch is installed
  echo "\e[32m Meilisearch is already installed\e[0m"
fi

# Install composer
if [ ! -f /usr/local/bin/composer ]
then
    curl -sS https://getcomposer.org/installer -o composer-setup.php
    HASH="$(wget -q -O - https://composer.github.io/installer.sig)"
    sudo php composer-setup.php --install-dir=/usr/local/bin --filename=composer
    rm composer-setup.php
else
  echo "\e[32m Composer is already installed\e[0m"
fi

# Logrotate
if [ ! -f /etc/logrotate.d/laravel ]
then
  cp $self_path/logrotate /etc/logrotate.d/laravel
  sed -i "s/--placeholder--/$hostname/" /etc/logrotate.d/laravel
else
  echo "\e[32m Logrotate is already installed\e[0m"
fi

# Install certbot
if [ ! -f /usr/bin/certbot ]
then
  apt install -y certbot python3-certbot-nginx
  sudo certbot --nginx -d $hostname -m "info@$hostname" --agree-tos --redirect --no-eff-email
else
  echo "\e[32m Certbot is already installed\e[0m"
fi

if [ ! -f /var/www/$hostname/.env ]
then
        sudo cp /var/www/$hostname/.env.example /var/www/$hostname/.env
        sed -i "s/APP_URL=.*/APP_URL=https:\/\/$hostname/" /var/www/$hostname/.env
        sed -i "s/APP_DEBUG=.*/APP_DEBUG=false/" /var/www/$hostname/.env
        sed -i "s/APP_ENV=.*/APP_ENV=production/" /var/www/$hostname/.env
        sed -i "s/APP_LOCALE=.*/APP_LOCALE=de_DE/" /var/www/$hostname/.env
        sed -i "s/DB_HOST=.*/DB_HOST=localhost/" /var/www/$hostname/.env
        sed -i "s/DB_DATABASE=.*/DB_DATABASE=$database/" /var/www/$hostname/.env
        sed -i "s/DB_USERNAME=.*/DB_USERNAME=$dbuser/" /var/www/$hostname/.env
        sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=$dbpassword/" /var/www/$hostname/.env
        sed -i "s/REDIS_HOST=.*/REDIS_HOST=localhost/" /var/www/$hostname/.env
        sed -i "s/REDIS_PASSWORD=.*/REDIS_PASSWORD=$redispassword/" /var/www/$hostname/.env
        sed -i "s/MEILISEARCH_KEY=.*/MEILISEARCH_KEY=$meilisearchKey/" /var/www/$hostname/.env
        sed -i "s/MEILISEARCH_HOST=.*/MEILISEARCH_HOST=localhost:7700/" /var/www/$hostname/.env
        sed -i "s/SCOUT_DRIVER=.*/SCOUT_DRIVER=meilisearch/" /var/www/$hostname/.env
        sed -i "s/SCOUT_QUEUE=.*/SCOUT_QUEUE=true/" /var/www/$hostname/.env
        sed -i "s/CACHE_DRIVER=.*/CACHE_DRIVER=redis/" /var/www/$hostname/.env
        sed -i "s/QUEUE_CONNECTION=.*/QUEUE_CONNECTION=redis/" /var/www/$hostname/.env
        sed -i "s/SESSION_DRIVER=.*/SESSION_DRIVER=redis/" /var/www/$hostname/.env
        touch /var/www/$hostname/storage/logs/laravel.log

        chown -R www-data:www-data /var/www/$hostname
        sudo -u www-data composer i --working-dir=/var/www/$hostname --no-dev --no-interaction --no-ansi --no-plugins --no-progress --optimize-autoloader
        sudo -u www-data php /var/www/$hostname/artisan key:generate --no-interaction --no-ansi
        sudo -u www-data php /var/www/$hostname/artisan storage:link
        sudo echo "* * * * * sudo -u www-data /usr/bin/php /var/www/$hostname/artisan schedule:run >> /dev/null 2>&1" >> /etc/crontab
        sudo -u www-data php /var/www/$hostname/artisan migrate --force --no-interaction --no-ansi
        sudo -u www-data php /var/www/$hostname/artisan scout:import
        sudo -u www-data php /var/www/$hostname/artisan scout:sync-index-settings
fi

sudo supervisorctl reread
sudo supervisorctl update

git config --global --add safe.directory /var/www/$hostname
cd /var/www/$hostname/
git pull

sudo -u www-data composer i  --working-dir=/var/www/$hostname --no-dev --no-interaction --no-ansi --no-plugins --no-progress --optimize-autoloader
sudo -u www-data php /var/www/$hostname/artisan optimize
sudo -u www-data php /var/www/$hostname/artisan migrate --force --no-interaction --no-ansi
sudo -u www-data php /var/www/$hostname/artisan storage:link
sudo -u www-data php /var/www/$hostname/artisan queue:restart
sudo -u www-data php /var/www/$hostname/artisan scout:import
sudo -u www-data php /var/www/$hostname/artisan scout:sync-index-settings
