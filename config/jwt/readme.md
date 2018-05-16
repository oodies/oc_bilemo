# Generating the Public and Private Key

In the folder 

Create a private key
```
$ openssl genrsa --out var/www/oc_bilemo/config/jwt /private -aes256 4096
```
This asks you for a password - give it one! Example **happyapi**

Last step: Create a public key
```
$ openssl rsa -pubout -in var/www/oc_bilemo/config/jwt/private.pem -out var/www/oc_bilemo/config/jwt/public.pem
``` 

We now have a **private.pem** and a **public.pem**
Finally, open **/var/www/oc_bilemo/.env** and add the **JWT_PASSPHRASE**, which for me is **happyapi**.
````text
JWT_PRIVATE_KEY_PATH=config/jwt/private.pem
JWT_PUBLIC_KEY_PATH=config/jwt/public.pem
JWT_PASSPHRASE=happyapi
````
