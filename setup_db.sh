#!/bin/bash
sudo -u postgres psql -c "CREATE DATABASE classifiedsperu;" 2>/dev/null || echo "DB may already exist"
sudo -u postgres psql -c "CREATE USER classifiedsuser WITH PASSWORD 'ClassPeru2026!';" 2>/dev/null || echo "User may already exist"
sudo -u postgres psql -c "GRANT ALL PRIVILEGES ON DATABASE classifiedsperu TO classifiedsuser;"
sudo -u postgres psql -c "ALTER DATABASE classifiedsperu OWNER TO classifiedsuser;"
echo "Database setup complete"
