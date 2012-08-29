# Vapor Control Panel, Web Interface (under development)

## User Setup

 1. Download the application
 2. Setup temp directory permissions
   - `chmod -R 777 app/tmp`
 3. Configure local webserver
 4. Visit `http://vapor.example.com/install`

## Requirements

 - Webserver
   - Apache2, Nginx, etc.
 - PHP 5.2.8+
 - SQL Database Server
   - MySQL, SQLite, etc.

## Setup Steps

 - [DONE] Randomly Generate Security.salt & Security.cipherSeed
   - [DONE] Replace codes in core.php with random values
 - [DONE] Gather DB config info
   - [DONE] Save `database.php` config
   - [DONE] Exec `Config/Sql/schema.sql`
   - [DONE] Exec `Config/Schema/db_acl.sql`
   - [DONE] Exec `Config/Sql/engines.sql`
   - [DONE] Exec `Config/Sql/games.sql`
   - [DONE] Insert SuperUser Group (`Group->insert()`)
   - [DONE] Create All ACOs
   - [DONE] Give SuperUser access to root ACO
 - [!!!!] Gather Email config info
   - [DONE] Save `email.php` config
   - [!!!!] Offer option to send a test email
 - [DONE] Gather username/password for SuperUser
   - [DONE] Add user via `User->add()`
 - [!!!!] Gather managed server info
   - [DONE] Add via `Server->add()`
   - [!!!!] Get information about server via handshake
 - [DONE] Mark instlal as completed

## Nginx Config
```
server {
    listen   80;
    server_name example.com www.example.com;

    #root needs to be to /install_dir/app/webroot/
    root   /var/www/example.com/public/app/webroot/;

    access_log /var/www/example.com/log/access.log;
    error_log /var/www/example.com/log/error.log;

    location / { #rewrite rules
        index  index.php index.html index.htm;
        try_files $uri $uri/ /index.php?$uri&$args;
    }

    location ~ \.php$ { #php configuration
        include /etc/nginx/fcgi.conf;
        fastcgi_pass    127.0.0.1:10005;
        fastcgi_index   index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```

## Apache2 Config
```
<VirtualHost *:80>
    ServerAdmin postmaster@example.com

    ServerName example.com
    ServerAlias www.example.com

    ErrorLog "/var/log/example.com-error.log"
    CustomLog "/var/log/example.com-access.log"

    DocumentRoot "/var/www/example.com/public/"

    <Directory "/var/www/example.com/public/">
        Options Indexes FollowSymLinks MultiViews
        AllowOverride All
        Order Allow,Deny
        Allow from all
    </Directory>
</VirtualHost>
```