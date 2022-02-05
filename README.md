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

ssh dockware@127.0.0.1 -p 2223
cd /var/www/html
mysql -uroot -proot --database="shopware" --execute="UPDATE sales_channel_domain SET url='http://127.0.0.1:8081' WHERE sales_channel_id <> X'98432def39fc4624b33213a56b8c944d'"
bin/console app:refresh # follow the wizard
bin/console app:activate AppBoilerplate
```

Visit the Shopware Admin on http://127.0.0.1:8081/admin, log in with admin / shopware. A new menu entry will be available under 'Settings' which takes you to your app.

## Let me know & spread the love
This boilerplate is available for free, and can be used in any way you want.
I do love it though to hear from projects that are using it, so let me know: @runelaenen on Twitter, @Rune Laenen on the Shopware Community slack, or rune@laenen.me for old-school e-mail.

Also a round of applause for @vienthuong for the [vienthuong/shopware-php-sdk](https://github.com/vienthuong/shopware-php-sdk) package.
