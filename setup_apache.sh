#!/bin/bash

# Set permissions
chown -R www-data:www-data /var/www/html/classifiedsperu
chmod -R 755 /var/www/html/classifiedsperu
chmod -R 775 /var/www/html/classifiedsperu/storage
chmod -R 775 /var/www/html/classifiedsperu/bootstrap/cache

# Create Apache virtual host
cat > /etc/apache2/sites-available/classifiedsperu.conf << 'EOF'
<VirtualHost *:80>
    ServerName classifiedsperu.ainitravel.com
    DocumentRoot /var/www/html/classifiedsperu/public

    <Directory /var/www/html/classifiedsperu/public>
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/classifiedsperu_error.log
    CustomLog ${APACHE_LOG_DIR}/classifiedsperu_access.log combined
</VirtualHost>
EOF

# Enable site and mod_rewrite
a2ensite classifiedsperu.conf
a2enmod rewrite
systemctl reload apache2

echo "Apache configured and reloaded"
echo "Site available at: http://classifiedsperu.ainitravel.com"
