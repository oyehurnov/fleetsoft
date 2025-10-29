# FleetSoft
## Develop a small RESTful application on Yii2 to manage a book library.
Spec https://docs.google.com/document/d/1VltHPHT_ip0B6NarKXo2uiFJhatzEb4WuWkiM7nIENo/edit?tab=t.0


# Yii2 REST Books API

## Requirements
- PHP 8+
- Composer
- MySQL or SQLite
- (Optional) Docker / docker-compose

## Install
1. Clone / create Yii2 basic app.
2. `composer install`
3. `composer require firebase/php-jwt`
4. Configure `config/db.php` with your DB credentials.
5. Set `params['jwtSecret']` in `config/params.php` (use a long random string).
6. Run migrations:

