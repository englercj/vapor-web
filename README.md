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

 - Randomly Generate Security.salt & Security.cipherSeed
   - Not sure how yet...
 - Gather DB config info
   - Save `database.php` config
   - Exec `Config/Sql/schema.sql`
   - Exec `Config/Schema/db_acl.sql`
   - Exec `Config/Sql/engines.sql`
   - Exec `Config/Sql/games.sql`
   - Insert SuperUser Group (`Group->insert()`)
   - Create ACOs using AclComponent
     - `$this->Acl->Aco->create(array('parent_id' => null, 'alias' => 'controllers'));`
     - `$this->Acl->Aco->save();`
 - Gather Email config info
   - Save `email.php` config
   - Offer option to send a test email
 - Gather username/password for SuperUser
   - Add user via `User->add()`
 - Gather managed server info
   - Add via `Server->add()`

## Setup Needs

 - Database Config
   - 'datasource' => 'Database/Mysql'
     - Database/Mysql          - MySQL 4 & 5,
     - Database/Sqlite         - SQLite (PHP5 only),
     - Database/Postgres       - PostgreSQL 7 and higher,
     - Database/Sqlserver      - Microsoft SQL Server 2005 and higher
   - 'persistent' => false,
   - 'host' => 'localhost',
   - 'login' => 'user',
   - 'password' => 'password',
   - 'database' => 'database_name',
   - 'prefix' => '',
 - SMTP Configuration
   - 'from' => array('site@localhost' => 'My Site'),
   - 'host' => 'localhost',
   - 'port' => 25,
   - 'timeout' => 30,
   - 'username' => 'user',
   - 'password' => 'secret',
   - 'client' => null,
   - 'log' => false
 - Security.salt [apg -m 64 -a 1 -n 1 -M NCL ; random alphanum 64 len]
 - Security.cipherSeed [apg -m 32 -a 1 -n 1 -M N ; random num 32 len]

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