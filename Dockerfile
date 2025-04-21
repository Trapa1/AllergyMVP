# Use PHP base image
FROM php:8.2-cli

# Set working directory
WORKDIR /var/www/html

# Copy all files including the database
COPY . .

# Expose port 8080
EXPOSE 8080

# Start PHP built-in server
CMD ["php", "-S", "0.0.0.0:8080"]

