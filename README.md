# PHP Challenge S2IT

Project made for the PHP challenge from S2it.
The project parses two models of XML (teacher and student).
The two accepted models are below:

**Teacher:**
```
<?xml version="1.0" encoding="UTF-8"?>
  <teachers>
    <teacher>
      <first_name>Professor 1</first_name>
      <last_name>Example 1</last_name>
      <email>professor@example.com</email>
      <room>123</room>
    </teacher>
  </teachers>
```
**Student:**
```
<?xml version="1.0" encoding="UTF-8"?>
  <students>
    <student>
      <first_name>Student</first_name>
      <last_name>Example</last_name>
      <phone>00 1111 2222</phone>
      <email>student@email.com</email>
    </student>
  </students>
```
Installation
------------

```
composer install
php app/console doctrine:database:create
php app/console doctrine:schema:update --force
echo "    xml_directory: '%kernel.root_dir%/../web/uploads/xml'" >> app/config/parameters.yml
php app/console server:run
```

Api demo user
-------------
```
INSERT INTO `user` (`id`, `username`, `apiKey`) VALUES (NULL, 'demo', 'tRLqd6JUBsKd7eVJ');
```
Tests
-----

```
cd app/
phpunit
```