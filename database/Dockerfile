# Simple Dockerfile for a MySQL service
# We keep it minimal since we mostly rely on the official image
FROM mysql:8.0

# Set a default charset and collation for better compatibility
ENV MYSQL_ROOT_PASSWORD=rootpassword
ENV MYSQL_DATABASE=Sojt-webapp
ENV MYSQL_USER=laravel
ENV MYSQL_PASSWORD=secret

# Expose default MySQL port
EXPOSE 3306

# Optional: place initialization SQL files in /docker-entrypoint-initdb.d
# COPY ./initdb.d /docker-entrypoint-initdb.d
