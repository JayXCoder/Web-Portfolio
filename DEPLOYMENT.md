# Automatic Deployment Setup

This guide shows how to set up automatic deployment when you push changes to the main branch.

## Prerequisites

1. **GitHub Repository**: Your code is already in GitHub
2. **Production Server**: VPS, shared hosting, or cloud platform
3. **Domain**: Your live website domain

## Option 1: VPS/Server Deployment (Recommended)

### Step 1: Set up GitHub Secrets

Go to your GitHub repository → Settings → Secrets and variables → Actions, add these secrets:

```
HOST=your-server-ip-or-domain.com
USERNAME=your-server-username
SSH_KEY=your-private-ssh-key
```

### Step 2: Server Setup

On your production server:

```bash
# Install required software
sudo apt update
sudo apt install nginx mysql-server php8.2-fpm php8.2-mysql php8.2-mbstring php8.2-xml php8.2-curl composer nodejs npm git

# Create project directory
sudo mkdir -p /var/www/portfolio
sudo chown $USER:$USER /var/www/portfolio

# Clone your repository
cd /var/www/portfolio
git clone https://github.com/JayXCoder/Web-Portfolio.git .

# Install dependencies
composer install --no-dev --optimize-autoloader
npm install
npm run build

# Set up environment
cp .env.example .env
# Edit .env with production settings
php artisan key:generate
php artisan migrate
php artisan storage:link
php artisan config:cache
```

### Step 3: Configure Nginx

Create `/etc/nginx/sites-available/portfolio`:

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /var/www/portfolio/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

Enable the site:
```bash
sudo ln -s /etc/nginx/sites-available/portfolio /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl reload nginx
```

### Step 4: Enable GitHub Actions

The workflow `.github/workflows/deploy-vps.yml` will automatically deploy when you push to main.

## Option 2: Docker Deployment

### Step 1: Create Dockerfile

```dockerfile
FROM php:8.2-fpm

# Install system dependencies
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nodejs \
    npm

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www

# Copy application
COPY . .

# Install dependencies
RUN composer install --no-dev --optimize-autoloader
RUN npm install && npm run build

# Set permissions
RUN chown -R www-data:www-data /var/www
RUN chmod -R 755 /var/www/storage

EXPOSE 9000
CMD ["php-fpm"]
```

### Step 2: Create docker-compose.yml

```yaml
version: '3.8'
services:
  app:
    build: .
    container_name: portfolio-app
    restart: unless-stopped
    working_dir: /var/www
    volumes:
      - ./:/var/www
      - ./docker/php/local.ini:/usr/local/etc/php/conf.d/local.ini
    networks:
      - portfolio

  nginx:
    image: nginx:alpine
    container_name: portfolio-nginx
    restart: unless-stopped
    ports:
      - "80:80"
      - "443:443"
    volumes:
      - ./:/var/www
      - ./docker/nginx/conf.d/:/etc/nginx/conf.d/
    networks:
      - portfolio

  db:
    image: mysql:8.0
    container_name: portfolio-db
    restart: unless-stopped
    environment:
      MYSQL_DATABASE: portfolio
      MYSQL_USER: portfolio
      MYSQL_PASSWORD: your_password
      MYSQL_ROOT_PASSWORD: root_password
    volumes:
      - dbdata:/var/lib/mysql
    networks:
      - portfolio

volumes:
  dbdata:
    driver: local

networks:
  portfolio:
    driver: bridge
```

### Step 3: Set up GitHub Secrets

Add these secrets to your GitHub repository:

```
DOCKER_USERNAME=your-dockerhub-username
DOCKER_PASSWORD=your-dockerhub-password
HOST=your-server-ip
USERNAME=your-server-username
SSH_KEY=your-private-ssh-key
```

## Option 3: Shared Hosting (cPanel, etc.)

For shared hosting, you'll need to use FTP/SFTP deployment:

```yaml
name: Deploy to Shared Hosting

on:
  push:
    branches: [ main ]

jobs:
  deploy:
    runs-on: ubuntu-latest
    
    steps:
    - name: Checkout code
      uses: actions/checkout@v4
      
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        
    - name: Setup Node.js
      uses: actions/setup-node@v4
      with:
        node-version: '20'
        cache: 'npm'
        
    - name: Install dependencies
      run: |
        composer install --no-dev --optimize-autoloader
        npm ci
        npm run build
        
    - name: Deploy via FTP
      uses: SamKirkland/FTP-Deploy-Action@v4.3.4
      with:
        server: ${{ secrets.FTP_SERVER }}
        username: ${{ secrets.FTP_USERNAME }}
        password: ${{ secrets.FTP_PASSWORD }}
        local-dir: ./
        server-dir: ./public_html/
```

## Testing Your Deployment

1. **Make a small change** to your code
2. **Commit and push** to main branch
3. **Check GitHub Actions** tab to see deployment progress
4. **Visit your live site** to verify changes

## Troubleshooting

### Common Issues:

1. **SSH Key Problems**: Ensure your SSH key has proper permissions (600)
2. **Permission Errors**: Check file ownership on server
3. **Database Issues**: Ensure production database is accessible
4. **Asset Building**: Verify Node.js and npm are installed

### Debug Commands:

```bash
# Check GitHub Actions logs
# Go to Actions tab in your GitHub repository

# Check server logs
sudo tail -f /var/log/nginx/error.log
sudo tail -f /var/log/php8.2-fpm.log

# Test deployment manually
ssh user@your-server "cd /var/www/portfolio && git pull && composer install && npm run build"
```

## Security Notes

- Never commit `.env` files with production credentials
- Use GitHub Secrets for sensitive information
- Enable HTTPS on your production server
- Keep your server and dependencies updated
- Use strong passwords and SSH keys

## Next Steps

1. Choose your deployment method
2. Set up the required secrets in GitHub
3. Configure your production server
4. Test the deployment workflow
5. Monitor your deployments

Your portfolio will now automatically deploy whenever you push changes to the main branch! 🚀
