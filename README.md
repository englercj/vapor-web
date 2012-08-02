# Vapor Control Panel, Web Interface (under development)

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