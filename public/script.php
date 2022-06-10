<?php
declare(strict_types=1);

use App\Facades\DB;

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/digitalstars/database/autoload.php';

$db = require __DIR__ . '/../config/db.php';

try {
    DB::init($db);

    $users = DB::query('select * from `users`')->all();

    foreach ($users as $user) {
        if (! notify($user)) {
            continue;
        }

        if (($email = email($user)) !== false) {
            send_email(
                $email,
                'email bot',
                $user['username'],
                'warning',
                "{$user['username']}, your subscription is expiring soon"
            );
        }
    }

} catch (Exception $e) {
    response([
        'error' => $e->getCode(),
        'message' => $e->getMessage(),
        'file' => $e->getFile(),
        'line' => $e->getLine(),
    ]);
}

function email(array $user)
{
    $email = DB::query('select * from `emails` where `id` = ?')->one([$user['email_id']]);
    if ($email['checked'] && $email['valid'] === 1) {
        return $email['email'];
    }

    if ($email['checked'] && $email['valid'] === 0) {
        return false;
    }

    $valid = check_email($email['email']);

    DB::query('update `emails` set `checked` = :checked, `valid` = :valid where `id` = :id')
    ->update(['checked' => 1, 'valid' => $valid, 'id' => $user['email_id']]);

    return $valid === 1 ? $email['email'] : false;
}

function send_email($email, $from, $to, $subj, $body)
{
    //Отсылает емейл. Функция работает от 1 секунды до 10 секунд.
    $post = rand(1, 10);
    sleep($post);
}

function check_email($email): int
{
    //Проверяет емейл на валидность и возвращает 0 или 1.
    //Функция работает от 1 секунды до 1 минуты. Вызов функции платный.
    $post = rand(1, 60);
    sleep($post);

    return rand(0,1);
}

/**
 * @throws Exception
 */
function notify(array $user): bool
{
    $date = new DateTimeImmutable($user['validts']);
    $now = new DateTimeImmutable('now');
    if ($date->diff($now)->days < 3) {
        return true;
    }

    return false;
}

function response(array $data)
{
    header('Content-type: application/json');
    echo json_encode($data, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
}