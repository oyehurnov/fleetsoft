# FleetSoft Yii2 REST Books API
## Develop a small RESTful application on Yii2 to manage a book library.
Spec https://docs.google.com/document/d/1VltHPHT_ip0B6NarKXo2uiFJhatzEb4WuWkiM7nIENo/edit?tab=t.0

## Requirements
- PHP 8+
- Composer
- MySQL
- Docker / docker-compose

## Install
1. Run docker compose up -d 
2. Permissions & Apache fix (if needed)
```
       docker exec -it yii2_app bash
       chmod -R 755 /app
       chmod -R 777 /app/yii2-app/runtime /app/yii2-app/web/assets

       cat <<'EOF' > /etc/apache2/sites-available/000-default.conf
       <VirtualHost *:80>
       DocumentRoot /app/yii2-app/web
       <Directory /app/yii2-app/web>
       AllowOverride All
       Require all granted
       Options Indexes FollowSymLinks
       </Directory>
       ErrorLog /var/log/apache2/error.log
       CustomLog /var/log/apache2/access.log combined
       </VirtualHost>
       EOF
    
       a2enmod rewrite
       service apache2 restart
       exit
       docker-compose restart
```
3. Create Yii2 basic app: composer create-project --prefer-dist yiisoft/yii2-app-basic /app/yii2-app
4. Run
```
       composer install
       composer require firebase/php-jwt
       composer require --dev phpunit/phpunit yiisoft/yii2-codeception
```
5. Run migrations.
7. Seed user and book tables (`php yii seed`)
8. Use the attached Postman collection (FleetSoft.postman_collection.json)
9. Test the API with Postman requests 

## Install
1. Start the app (built-in server)
   ```
       php -S 127.0.0.1:8080 -t web
   ```
3. Run tests
   ```
       vendor/bin/phpunit --testdox
   ```

