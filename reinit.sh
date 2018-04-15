git pull
php bin/console c:c --env prod
chmod -R 777 var/cache var/logs var/sessions
bin/console a:i --env prod
bin/console a:d --env prod
bin/console fos:js-routing:dump --env prod
