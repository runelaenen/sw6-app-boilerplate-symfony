# Shopware 6 app boilerplate with Symfony backend
This boilerplate template can be used to get up and running with a docker-based dev setup for Shopware 6 App development, with a Symfony backend.

Some knowledge of Symfony, Shopware, and how the app system works is necessary to use this boilerplate. Please read the Shopware docs about the App System before continuing.

To get up an running quickly, run the following commands:
```
docker-compose up -d

ssh dockware@127.0.0.1 -p 2222
mysql -uroot -proot --execute="CREATE DATABASE app;"
cd /var/www/html
bin/console doctrine:migrations:migrate -q
exit

ssh dockware@127.0.0.1:2223
cd /var/www/html
bin/console app:refresh # follow the wizard
bin/console app:activate AppBoilerplate
```

This boilerplate is available for free, and can be used in any way you want.
I do love it though to hear from projects that are using it, so let me know: @runelaenen on Twitter, @Rune Laenen on the Shopware Community slack, or rune@laenen.me for old-school e-mail. 