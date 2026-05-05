#!/bin/bash
set -e

for f in /etc/apache2/sites-available/ainitravel.com.conf /etc/apache2/sites-available/ainitravel.com-le-ssl.conf; do
  sed -i 's|Alias /clasificados /var/www/html/classifiedsperu/public|Alias /clasificados/ /var/www/html/classifiedsperu/public/|g' "$f"
  sed -i 's|Alias /clasificados/ /var/www/html/classifiedsperu/public$|Alias /clasificados/ /var/www/html/classifiedsperu/public/|g' "$f"
  if ! grep -q 'RedirectMatch 302 \^/clasificados\$ /clasificados/' "$f"; then
    sed -i '/CustomLog/a\    RedirectMatch 302 ^/clasificados$ /clasificados/' "$f"
  fi
done

apache2ctl configtest
systemctl reload apache2
