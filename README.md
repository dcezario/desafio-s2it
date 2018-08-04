# PHP Challenge S2IT

Project made for the PHP challenge from S2it

Installation
------------

```
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
echo "    xml_directory: '%kernel.root_dir%/../web/uploads/xml'" >> app/config/parameters.yml
php app/console server:run
```

Tests
-----

```
cd app/
phpunit
```