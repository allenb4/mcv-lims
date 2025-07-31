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
   
   ## If Fatal error: require(): Failed opening required '/var/www/vendor/autoload.php' (include_path='.:/usr/local/lib/php') has been encountered
   **Run**
   docker-compose run app composer install

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


# Using WSL as Your Localhost with Docker

This guide provides step-by-step instructions for configuring **WSL (Windows Subsystem for Linux)** as your localhost environment for Docker development. It also includes steps to integrate **GitHub Desktop** for managing repositories effectively.

---

## Prerequisites

Ensure you have the following tools and configurations before proceeding:

1. **Windows 10/11** with the latest updates.
2. **Windows Subsystem for Linux 2 (WSL 2)** installed and configured.
3. **Docker Desktop** installed on Windows.
4. **GitHub Desktop** installed on Windows.
5. **A Linux Distribution** (e.g., Ubuntu) installed in WSL.

---

## Step 1: Install and Configure WSL

1. **Install WSL**:
   Open PowerShell as Administrator and run:
   ```bash
   wsl --install
   ```
   This installs the default Linux distribution and sets up WSL 2.

2. **Set WSL Version to 2**:
   If WSL 2 isn’t the default, set it manually:
   ```bash
   wsl --set-default-version 2
   ```

3. **Install Additional Distributions (Optional)**:
   If you want to use a specific Linux distribution, download it from the Microsoft Store and set it to WSL 2:
   ```bash
   wsl --set-version <distribution-name> 2
   ```

4. **Update WSL Kernel**:
   Download the latest WSL kernel update package [here](https://aka.ms/wsl2kernel) and install it.

5. **Launch Your Linux Distribution**:
   Open WSL from the Start menu or by typing `wsl` in the terminal. Complete the initial setup (e.g., setting username and password).

---

## Step 2: Install Docker Desktop

1. **Download Docker Desktop**:
   [Download Docker Desktop](https://www.docker.com/products/docker-desktop) and install it on Windows.

2. **Enable WSL 2 Backend**:
   - Open Docker Desktop.
   - Go to **Settings > General** and enable **Use the WSL 2 based engine**.

3. **Integrate Docker with WSL**:
   - Navigate to **Settings > Resources > WSL Integration**.
   - Enable Docker integration for your Linux distributions (e.g., Ubuntu).

4. **Verify Docker Installation in WSL**:
   Open your WSL terminal and run:
   ```bash
   docker --version
   docker run hello-world
   ```
   If the commands execute successfully, Docker is correctly set up in WSL.

---

## Step 3: Set Up Git in WSL

1. **Install Git in WSL**:
   ```bash
   sudo apt update
   sudo apt install git
   ```

2. **Configure Git**:
   ```bash
   git config --global user.name "Your Name"
   git config --global user.email "your_email@example.com"
   ```

3. **Generate an SSH Key (Optional)**:
   If you plan to use SSH for GitHub operations:
   ```bash
   ssh-keygen -t ed25519 -C "your_email@example.com"
   cat ~/.ssh/id_ed25519.pub
   ```
   Add the output to your GitHub account under **Settings > SSH and GPG keys**.

4. **Test SSH Access to GitHub**:
   ```bash
   ssh -T git@github.com
   ```

---

## Step 4: Configure GitHub Desktop with WSL

1. **Install GitHub Desktop**:
   Download and install [GitHub Desktop](https://desktop.github.com/) on Windows.

2. **Set WSL as the Default Shell**:
   - Open GitHub Desktop.
   - Navigate to **File > Options > Advanced**.
   - Under **Shell**, select **Custom** and enter:
     ```plaintext
     C:\Windows\System32\wsl.exe
     ```

3. **Clone Repositories into WSL**:
   - In GitHub Desktop, click **File > Clone Repository**.
   - Set the local path to a WSL directory, e.g.:
     ```plaintext
     \wsl$\Ubuntu\home\<your-username>\projects
     ```
   - Replace `Ubuntu` with the name of your WSL distribution and `<your-username>` with your WSL username.

4. **Access Repositories in WSL**:
   Navigate to the repository folder from your WSL terminal:
   ```bash
   cd /home/<your-username>/projects/<repository-name>
   git status
   ```

---

## Step 5: Use Docker in WSL

1. **Run Docker Commands in WSL**:
   With Docker Desktop running, execute Docker commands in WSL. For example:
   ```bash
   docker pull nginx
   docker run -d -p 8080:80 nginx
   ```
   Access the running container at `http://localhost:8080` in your browser.

2. **Bind Mount Directories**:
   Use directories within WSL for bind mounts to improve performance. Example:
   ```bash
   docker run -v /home/<your-username>/projects:/app -w /app node:16 node index.js
   ```

3. **Manage Docker Containers**:
   Common commands for managing Docker containers include:
   ```bash
   docker ps                 # List running containers
   docker stop <container-id>  # Stop a container
   docker rm <container-id>    # Remove a container
   ```

4. **Run Docker Compose (Optional)**:
   Install Docker Compose in WSL:
   ```bash
   sudo apt install docker-compose
   ```
   Use a `docker-compose.yml` file to manage multi-container applications:
   ```bash
   docker-compose up
   ```

---

## Step 6: Additional Development Tools

1. **VS Code with WSL**:
   - Install the [Remote - WSL extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-wsl).
   - Open VS Code and click **Remote Explorer > WSL** to work directly in your WSL environment.

2. **Database Containers**:
   Use Docker to run database containers, e.g., MySQL:
   ```bash
   docker run --name mysql -e MYSQL_ROOT_PASSWORD=root -d -p 3306:3306 mysql:latest
   ```

---

## Best Practices

- **Use WSL File System**: Store project files in `/home/<your-username>` for optimal performance.
- **Keep Software Updated**: Regularly update WSL, Docker, and your Linux distribution to avoid compatibility issues.
- **Restart Services if Needed**:
   ```bash
   wsl --shutdown
   ```

---

## Troubleshooting

1. **Docker Commands Not Found in WSL**:
   Ensure Docker Desktop is running and WSL integration is enabled.

2. **Cannot Access Localhost**:
   Check if the container ports are exposed correctly, and ensure no firewalls block connections.

3. **Performance Issues**:
   Avoid using Windows file paths (`/mnt/c/...`) in WSL; instead, work within the native WSL file system.

---

With this setup, you can efficiently use WSL as your localhost environment with Docker and GitHub Desktop. Customize these steps to fit your development workflow.