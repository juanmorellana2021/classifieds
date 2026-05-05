#!/bin/bash
set -e

cat > /etc/apache2/sites-enabled/ainitravel.com.conf <<'EOF'
<VirtualHost *:80>
    ServerAdmin webadmin@ainitravel.com
    ServerName ainitravel.com
    ServerAlias www.ainitravel.com

    DocumentRoot /var/www/html/ainitravel.com
    DirectoryIndex coming-soon.html public_booking.php index.php index.html

    <Directory /var/www/html/ainitravel.com>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Alias /clasificados/ /var/www/html/classifiedsperu/public/
    <Directory /var/www/html/classifiedsperu/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    RedirectMatch 302 ^/clasificados$ /clasificados/

    ErrorLog /var/log/apache2/ainitravel.com-error.log
    CustomLog /var/log/apache2/ainitravel.com-access.log combined

    RewriteEngine on
    RewriteCond %{REMOTE_ADDR} !127.0.0.1
    RewriteCond %{SERVER_NAME} =ainitravel.com [OR]
    RewriteCond %{SERVER_NAME} =www.ainitravel.com
    RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=permanent]
</VirtualHost>
EOF

cat > /etc/apache2/sites-enabled/ainitravel.com-le-ssl.conf <<'EOF'
<IfModule mod_ssl.c>
<VirtualHost *:443>
    ServerAdmin webadmin@ainitravel.com
    ServerName ainitravel.com
    ServerAlias www.ainitravel.com

    DocumentRoot /var/www/html/ainitravel.com
    SetEnv OLLAMA_URL http://72.60.1.16:11434
    SetEnv OLLAMA_MODEL qwen2.5:7b
    DirectoryIndex coming-soon.html public_booking.php index.php index.html

    <Directory /var/www/html/ainitravel.com>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    Alias /clasificados/ /var/www/html/classifiedsperu/public/
    <Directory /var/www/html/classifiedsperu/public>
        Options FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    RedirectMatch 302 ^/clasificados$ /clasificados/

    ErrorLog /var/log/apache2/ainitravel.com-error.log
    CustomLog /var/log/apache2/ainitravel.com-access.log combined

    SSLCertificateFile /etc/letsencrypt/live/ainitravel.com/fullchain.pem
    SSLCertificateKeyFile /etc/letsencrypt/live/ainitravel.com/privkey.pem
    Include /etc/letsencrypt/options-ssl-apache.conf
</VirtualHost>
</IfModule>
EOF

apache2ctl configtest
systemctl reload apache2
