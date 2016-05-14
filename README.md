![Sample-eshop-yii Logo](https://github.com/ghrabbit/sample-eshop-yii/master/images/sample-eshop-d.png)

##Введение

Это php приложение - пример магазина на основе фреймворка YII. 
Все view сделаны с использованием шаблонов mustache. Благодаря 
чему приложение легко переносится на другие платформы, которые поддерживают mustache. 

##Установка

Чтобы установить приложение прежде всего скопируйте релиз с github:

$ git clone -b master https://github.com/ghrabbit/sample-eshop-yii.git sample-eshop-yii

Перейдите в каталог sample-eshop-yii/protected  и установите зависимости с помощь bower и composer:

$ bower install
$ composer install 

##Конфигурация

Создайте базу данных. Файлы в формате sql находятся в каталоге /protected/data. 
Отредактируйте данные вашей базы данных в файле /config/main.php. По умолчанию используется Postgresql.
Настройте вебсервер, указав месторасположение приложения. Если нужно измените файл .htaccess.
