# INCONNECT
Server Aplikasi dibangun dengan Bahasa pemograman PHP, dengan menggunakan framework Laravel Versi 9.0, dan database Mysql. 
Server ini digunakan sebagai backend aplikasi webadmin, penyimpanan data (database), dan API Service untuk aplikasi Frontend.

## Requirement & Server Installation
Memory 4GB
CPU 4 CPU
Storage 80GB SSD

## Operating System, Software & Service Requirement
1.	Linux Ubuntu 22.04
2.	Webserver Nginx
3.	PHP-8.1
4.	Mysql8
5.	SSL & TLS (HTTPS)
6.	UFW (FIREWALL)
7.	Supervisord
8.	Git
9.	Composer-2
10.	SMTP Server, Untuk pengiriman Email
11.	NodeJS & NPM
12.	Redis
13.	Mosquito, MQTT Broker

## Installation

#### 1.	Clone Repository (Gitlab)

```bashd
sudo git clone https://gitlab.com/damkar.andika2000/mfds.git mfds
```

####  2. Restore Database Mysql dbmfds.sql
contoh salinan ada di file ".env.example", copy ".env"

```bashd
sudo cp .env.example  .env.example
```

####  2. Setup Environtment Variable
Create file .env di root directory, atau copy file ".env.example", copy ".env".
<br>
Kemudian edit file tersebut seperti text dibawah ini
```bash
CONFIGURATION EXAMPLE
---------------------
APP_NAME=MFDS
APP_ENV=production
APP_KEY=base64:zx1Cg+1qOKzVoN08tXPRsefLD+hwpHUyXJMA9J+lDTY=
APP_URL=mfds.andika2000.my.id

SERVER_HOST_NAME=DEVEL

APP_DEBUG=true
DEBUGBAR_ENABLED=false
LOG_CHANNEL=stack

DB_CONNECTION=mysql
DB_HOST=host.docker.internal
DB_PORT=3306
DB_DATABASE=mfds
DB_USERNAME=root
DB_PASSWORD=admin

CACHE_DRIVER=redis
REDIS_CLIENT=predis
REDIS_HOST=localhost
REDIS_PASSWORD=null
REDIS_PORT=6379

BROADCAST_DRIVER=log
QUEUE_CONNECTION=database
SESSION_DRIVER=file
SESSION_LIFETIME=1440

MAIL_DRIVER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=2525
MAIL_USERNAME=e81dbbc2c56733
MAIL_PASSWORD=7d45e56621de5d
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@test.com
MAIL_FROM_NAME=noreply

PUSHER_APP_ID=
PUSHER_APP_KEY=
PUSHER_APP_SECRET=
PUSHER_APP_CLUSTER=mt1

MIX_PUSHER_APP_KEY="${PUSHER_APP_KEY}"
MIX_PUSHER_APP_CLUSTER="${PUSHER_APP_CLUSTER}"

SITE_HEADER="MFDS"
SITE_TITLE="MFDS KOTA"
SITE_FOOTER="PEMADAM KEBAKARAN KOTA"
SITE_LOGIN_TITLE=MFDS
SITE_LOGIN_SUBTITLE="MANAGEMENT FIRE DEPARTMENT SYSTEM"
SITE_DEFAULT_PROVINCE_ID=32
SITE_DEFAULT_CITY_ID=3204
SITE_DEFAULT_COORDINATE_LAT=-7.0256647412451505
SITE_DEFAULT_COORDINATE_LONG=107.52519970908075
SITE_SOCKET_IO_PRIVATE_HOST=localhost
SITE_SOCKET_IO_PRIVATE_PORT=80
SITE_SOCKET_IO_PUBLIC_HOST=example.com
SITE_SOCKET_IO_PUBLIC_PORT=443

MQTT_DEBUGING=false
MQTT_HOST=localhost
MQTT_USERNAME=iot
MQTT_PASSWORD=passWord
MQTT_PORT=1883
MQTT_TOPIC=kab-bdg
MQTT_CLIENT_ID=server_mfds

FCM_SERVER_KEY=AAAA8_H0OkI:APA91bHirFOSo0vsmHt0_zxhgV77YtvCRgd-euDo7iI-qXUbNiTY5ug9LM1qfEkViGf_KErZYG4r-HPvXjabtDUpScT7PiGTMq4FB7UWWkcdHEMtRXatJpQnJZl3QhWpK-BUmkV3
```


#### 3. Create Storage Framwork Directory 
```bash
sudo mkdir storage/framework/sessions
sudo mkdir storage/framework/views
sudo mkdir storage/framework/cache
sudo mkdir storage/app/public/uploads
```

#### 4. Setup Composer
```cmd
composer install
```

#### 5. Generate Key
```cmd
php artisan key:generate
```

#### 6. Clear Cache
```bash
php artisan cache:clear
php artisan route:clear
php artisan config:clear 
php artisan view:clear
```

#### Storage Link
```bash
php artisan storage:link
```

#### Config Supervisor
Buatlah file dengan nama "etc/supervisor/cond.d/worker.conf".
```bash
[program:worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artisan queue:work --queue=high,medium,low,default
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/storage/logs/worker.log
```

Buatlah file dengan nama "etc/supervisor/cond.d/mqtt_subscribe.conf".

```bash
[program:mqtt_subscribe]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/artisan mqtt:subscribe --queue=high,medium,low,default
autostart=true
autorestart=true
user=www-data
numprocs=1
redirect_stderr=true
stdout_logfile=/var/www/storage/logs/mqtt_subscribe.log
```

Kemudian jalankan service supervisord, sbb:
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl reload
```


## Contributing
Pull requests are welcome. For major changes, 
please open an issue first to discuss what you would like to change.
Please make sure to update tests as appropriate.
andika2000.blogspot.com, Email andika2000@gmail.com
