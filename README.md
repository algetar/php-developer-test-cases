###Setup
```
git clone git@github.com:algetar/php-developer-test-cases.git
cd php-developer-test-cases
git checkout main --
rm -rf docker-compose.yml .env
cp docker-compose.yml.dist docker-compose.yml && cp .env.dist .env 
docker-compose up -d
docker exec -it app composer install
```
###crontab
crontab -e 

добавить в конце
* * * * * path to project/sender.sh >> /var/log/cron.log 2>&1
# Don't remove the empty line at the end of this file. It is required to run the cron job

Вы разрабатываете сервис для рассылки уведомлений об истекающих подписках. 

Примерно за три дня до истечения срока подписки, нужно отправить письмо пользователю с текстом "{username}, your subscription is expiring soon". 

Имеем следующее 
----

1.Таблица users в DB с пользователями (1 000 000 строк): 
username — имя 
email — емейл 
validts — unix ts до которого действует ежемесячная подписка 
confirmed — 0 или 1 в зависимости от того, подтвердил ли пользователь свой емейл по ссылке
(пользователю после регистрации приходит письмо с уникальный ссылкой на указанный емейл, если он нажал на ссылку в емейле в этом поле устанавливается 1) 

2. Таблица emails в DB с данными проверки емейл на валидность: 
email — емейл 
checked — 0 или 1 (был ли проверен) 
valid — 0 или 1 (является ли валидным) 

3. Функция check_email( $email ) 
Проверяет емейл на валидность и возвращает 0 или 1. Функция работает от 1 секунды до 1 минуты. Вызов функции платный. 

4. Функция send_email( $email, $from, $to, $subj, $body ) 
Отсылает емейл. Функция работает от 1 секунды до 10 секунд. 

Ограничения:
➔ Необходимо регулярно отправлять емейлы об истечении срока подписки, но только на те емейлы, на которые письмо точно дойдёт. 
➔ Можно использовать cron. 
➔ Можно создать необходимые таблицы в DB или изменить существующие. 
➔ Можно использовать вызов http://localhost/script.php для вызова своих скриптов. 
➔ Для функций check_email и send_email нужно написать "заглушки".
➔ Не нужно использовать ООП. 
➔ Можно задавать вопросы и уточнять условия задачи. 
➔ Код разместить в GitHub.
