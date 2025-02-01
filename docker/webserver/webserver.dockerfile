# Use Alpine as a lightweight base for NGINX
FROM nginx:alpine

# Set working directory
WORKDIR /var/www/html

# Copy Laravel files
COPY ./src/api .

# Copy custom NGINX configuration
COPY ./docker/webserver/nginx.conf /etc/nginx/conf.d/default.conf

# Expose the port
EXPOSE 80

# Start NGINX
CMD ["nginx", "-g", "daemon off;"]