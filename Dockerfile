# Use the official PHP 8.2 image
FROM php:8.2-cli

# Set the working directory in the container
WORKDIR /var/www/html

# Copy all files to the working directory
COPY . .

# Expose port 8080 for the application
EXPOSE 8080

# Run PHP's built-in server
CMD ["php", "-S", "0.0.0.0:8080"]
rm database.sqlite  #