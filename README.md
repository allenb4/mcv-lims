# Getting Started with Dockerized Laravel Environment

This guide will help you to set up and start the Docker container for the Laravel 7 project with PHP 7.4, MySQL 8.0, and phpMyAdmin.

---

## Prerequisites
Before you begin, ensure you have the following installed on your system:

1. **Docker**
2. **Docker Compose**

---

## Steps to Start the Containers

1. **Clone the Repository**
   ```bash
   git clone <repository-url>
   cd <repository-directory>
   ```

2. **Build and Start the Containers**
   Run the following command to build and start the Docker containers:
   ```bash
   docker-compose up --build
   ```
   This will:
   - Build the `app` container based on the `Dockerfile`.
   - Start the `app`, `db`, and `phpmyadmin` services.

3. **Access the Application**
   - **Laravel App**: Visit `http://localhost:9000`.
   - **phpMyAdmin**: Visit `http://localhost:8081`.

4. **Run Laravel Commands**
   To run Artisan or other Laravel commands inside the container, use:
   ```bash
   docker exec -it laravel7-app bash
   ```
   Inside the container, you can run commands like:
   ```bash
   php artisan migrate
   php artisan serve
   ```

5. **Stop the Containers**
   To stop the running containers:
   ```bash
   docker-compose down
   ```

---

## Configuration Details

### Docker Compose File
- **App Service**
  - Image: `laravel7-php7.4`
  - Port: `9000`
  - Mounted Volumes:
    - Laravel source code to `/var/www`
    - Custom `php.ini` configuration
- **Database (MySQL)**
  - Image: `mysql:8.0`
  - Port: `3307`
  - Root credentials:
    - Username: `root`
    - Password: `root`
  - Database Name: `laravel`
  - Volume: Persistent storage for database data
- **phpMyAdmin**
  - Image: `phpmyadmin/phpmyadmin`
  - Port: `8081`

### Dockerfile
The `Dockerfile` is configured to:
- Use `php:7.4-fpm`
- Install necessary PHP extensions for Laravel
- Install Composer
- Set permissions for Laravel

---

## Troubleshooting

### Common Issues
1. **Port Conflicts**:
   Ensure ports `9000`, `3307`, and `8081` are not being used by other services.

2. **Missing Dependencies**:
   If you encounter errors regarding missing PHP extensions, verify that they are installed and enabled in the `Dockerfile` or your local system.

3. **Database Connection Issues**:
   Ensure the `DB_HOST`, `DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, and `DB_PASSWORD` values in `.env` match those in `docker-compose.yml`.

4. **Permission Errors**:
   Run the following command to fix permission issues:
   ```bash
   sudo chmod -R 775 .
   sudo chown -R $USER:$USER .
   ```

---

## Additional Commands

- **View Logs for a Service**:
  ```bash
  docker logs <container_name>
  ```
  Example:
  ```bash
  docker logs laravel7-app
  ```

- **Remove Containers, Networks, and Volumes**:
  ```bash
  docker-compose down -v
  ```

---

You're all set! Happy coding! 🎉

