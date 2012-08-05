# Vapor Control Panel, Web Interface (under development)

## Setup Steps

 - Randomly Generate Security.salt & Security.cipherSeed
 - Database Config
 - Email Config
 - SuperUser Config
 - Add a server

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
    server_name example.com www.example.com; #CHANGE TO YOUR DOMAIN

    # root directive should be global
    root   /var/www/example.com/public/app/webroot/; #CHANGE TO YOUR ROOT

    access_log /var/www/example.com/log/access.log; #CHANGE TO YOUR LOG
    error_log /var/www/example.com/log/error.log; #CHANGE TO YOUR LOG

    location / {
        index  index.php index.html index.htm;
        try_files $uri $uri/ /index.php?$uri&$args;
    }

    location ~ \.php$ { #CHANGE TO YOUR PHP CONFIG
        include /etc/nginx/fcgi.conf;
        fastcgi_pass    127.0.0.1:10005;
        fastcgi_index   index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    }
}
```