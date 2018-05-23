# E-Commerce API
-----------------------------------------
Project number 7 of Openclassrooms "Developpeur d'application PHP / Symfony" cursus

The objectif of this project is to create a web service with an API.

## Code quality
[![Codacy Badge](https://api.codacy.com/project/badge/Grade/38afc8b330ff45feba8958f6bead3f67)](https://www.codacy.com/app/sebastien.chomy/oc_bilemo?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=sebastien-chomy/oc_bilemo&amp;utm_campaign=Badge_Grade)

## Demontration
Preview example : [http://bilemo.oodie.fr](http://bilemo.oodie.fr)
- **/login**  Authenticate
- **/tokens** Get JWT
- **/apidoc** API Documents

## Installation

### 1 - Download or clone the repository git
```console
git clone https://github.com/sebastien-chomy/oc_bilemo.git my_project
```

### 2 - Generating the Public and Private Key
from **/my_project/config/jwt/** 

Create the Public and Private key to access the API by reading the following instructions [Readme](/config/jwt/readme.md)

### 3 - Download dependencies
from **/my_project/**
```console
composer install
``` 
Before you start using Composer, you must first install it on your system.
https://getcomposer.org/


## Create database, schema and load data fixtures 
Follow these steps
 
### 1 - Create database
From **/my_project/**
```
php bin/console doctrine:database:create
```

### 2 - Create schema
From **/my_project/**
```console
php bin/console doctrine:schema:create
```
OR
```console
php bin/console doctrine:schema:update --force
```

### 3 - Fixtures of data
From **/my_project/**
```console
php bin/console doctrine:fixtures:load
```

### 4 - Preparation
From **/my_project/**
```console
php bin/console cache:clear --env=prod 
```

## Configuration
For that project works it is necessary to change a file **/my_project/.env**

```ini
###> symfony/framework-bundle ###
APP_ENV=<Your environment dev|test|prod>
APP_SECRET=d6e73c5232ea0cabd4c4f86a69653eeb
###< symfony/framework-bundle ###

###> doctrine/doctrine-bundle ###
# Format described at http://docs.doctrine-project.org/projects/doctrine-dbal/en/latest/reference/configuration.html#connecting-using-a-url
# For an SQLite database, use: "sqlite:///%kernel.project_dir%/var/data.db"
# Configure your db driver and server_version in config/packages/doctrine.yaml
DATABASE_URL=mysql://db_user:db_password@127.0.0.1:3306/db_name
###< doctrine/doctrine-bundle ###

###> lexik/jwt-authentication-bundle ###
# Key paths should be relative to the project directory
JWT_PRIVATE_KEY_PATH=config/jwt/private.pem
JWT_PUBLIC_KEY_PATH=config/jwt/public.pem
JWT_PASSPHRASE=<Your passphrase>
JWT_TTL=3600
###< lexik/jwt-authentication-bundle ###
```

## VirtualHost

```ApacheConfig
<VirtualHost *:80>
	ServerName oc_bilemo.local
	DocumentRoot "path/to/my/project/public"
	AddDefaultCharset utf-8

	<Directory  "path/to/my/project/public">
		DirectoryIndex index.php
		Options Indexes FollowSymlinks
		Require all granted
		AllowOverride None

		<IfModule rewrite_module>
			RewriteEngine On
			RewriteCond %{HTTP:Authorization} ^(.*)
			RewriteRule .* - [e=HTTP_AUTHORIZATION:%1]						
			RewriteCond %{REQUEST_FILENAME} -f
			RewriteRule ^ - [L]
			RewriteRule ^ index.php [L]
		</IfModule>
	</Directory>
	ErrorLog logs/oc_bilemo-error.log
	CustomLog logs/oc_bilemo-access.log combined
</VirtualHost>
```

## Usage

### Authentication

For be authentified you need to obtain an access token from JWT.

For obtained this token you need to connect with your username and you password on Json Format on this url : **/apilogin**.
Use [POSTMAN](https://www.getpostman.com)
> **POST** http://oc_bilemo.local/apilogin
>> body : chosen 'raw' JSON and write

```json
{ 
    "username": "customer_1",
    "password": "12345"
}
```
Send the request and recevied the token
```json
{
    "token": "eyJhbGciOiJSUzI1NiJ9.eyJyb2xlcyI6WyJST0xFX0FQSV9VU0VSIl0sInVzZXJuYW1lIjoiY3VzdG9tZXJfMSIsImlhdCI6MTUyNzA3NTMyMiwiZXhwIjoxNTI3MDc4OTIyfQ.Q0boOWli_pNPZmby6WZp08_Ks8970Zjt1pqt6XOz5nt-NtZKEvPI51ErTkMLhxUtGqEadYvGJIyQ-xtd_HdNsTIhzSUV4BzC6R2_Wgt7Cmq7B1XPmDyQAyC_6XjMgSSjhO8QhNOFdLQ5B0ybHQBbhbya9Vz3URc6jQmihdq4jK9w_JwK_5IidOgECJOCTMF9IyEgVRSsH50zEzpFfFkXUahZ97g_O8NZYYARlEy5JLxhAgr7Aj_G-YDSrdlD939yFDL1mZdawhdKSAuqeC-2krJnILMMCtn6V8MJEnOCr59LDxWNi3Ucw4GbdebXzOPDObo9oPlVgfwYf3bniYfOZPQHPUO3XbgfHQegJd8SwOiiQKOb9JszD_aERGH7HNWasa94sq_tp2Inooh5rZG6siLl3iI0oceTeTM5ynelK_ZflYRYppUM2YvGZqkkXkG1mrBTi9CRH3FGIY86yEoZTSmI4dJ80ahJW_g38H1aJBfcnqSiu8-Ix5egs-qdhtQj28wPoFJIFfr7HXoVaR3lXHyTMfKmtYjUgiwF0jzf-b1XIy68Ro_OCUKS4xLnHvPm_nqoQBCFtAZ2Ps5mmf1l86Ux4lSOSebzT8ToSSdNKR-MfZnEh_NsmKDLulEgb_MTLJPrBGGaAEVzKsw_-DrdOpDZViAu8l50YvKPuh5f-kw"
}
```

------------------------------------------
Or from the frontend on this url : **/login** then **/tokens** to obtained this token.

### API Document

[Screenshot ApiDoc](/doc/img/screenshot-ApiDoc.png)






