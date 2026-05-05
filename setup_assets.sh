#!/bin/bash
cd /var/www/html/classifiedsperu

# Replace app.js to import Bootstrap
cat > resources/js/app.js << 'EOF'
import './bootstrap';
import 'bootstrap';
EOF

# Replace app.css to import Bootstrap
cat > resources/css/app.css << 'EOF'
@import 'bootstrap/dist/css/bootstrap.min.css';
EOF

# Build assets
npm run build 2>&1 | tail -10
echo "Assets built"
