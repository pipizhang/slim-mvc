# Slim MVC Skeleton

### Purpose
The PHP [Slim](https://www.slimframework.com/) micro framework MVC Skeleton:

1. Provides a foundation for building challenges or creating a new Slim MVC base application.
2. Demonstrates a reasonable set of practices around building Slim MVC base applications.

### Requirements
* PHP 5.3.3+ compiled with the cURL extension.
* A recent version of cURL 7.16.2+ compiled with OpenSSL and zlib.
* Apache Server support .htaccess files.

### Installation
1. Install composer in your project (refer to https://getcomposer.org/doc/00-intro.md):
   ```
   curl -s https://getcomposer.org/installer | php
   ```

2. Install dependencies via composer:
Run composer command in "test_app" folder
   ```
   php composer.phar install
   ```

3. Change the folder permissions for the storage folder:
   ```
   chmod 777 -R app/storage
   ```

4. Set Apache VHost, for example:
Rename "test_app" folder to "www.example.com" then put it to /var/sites/, modify Apache configuration file like below and restart Apache.
   ```
   <VirtualHost>
       ServerName www.example.com
       DocumentRoot "/var/sites/www.example.com/public"
   </VirtualHost>
   ```

