<?php
declare(strict_types=1);

namespace App\Facades;

use PDO;
use PDOStatement;

class DB
{
    private static ?PDO $db = null;

    /** @var PDOStatement|false $statament */
    private $statement;

    public static function init($options) {
        $dsn = sprintf(
            '%s:host=%s;dbname=%s',
            $options['driver'],
            $options['host'],
            $options['db_name']
        );

        static::$db = new PDO($dsn, $options['username'], $options['password']);
    }

    public function db(): PDO
    {
        return static::$db;
    }

    public static function query($sql, array $params = []): self
    {
        return (new static())->prepare($sql);
    }

    public function prepare($sql): self
    {
        $this->statement = static::$db->prepare($sql);

        return $this;
    }

    public function all(): array
    {
        $this->statement->execute();

        return $this->statement->fetchAll(PDO::FETCH_ASSOC);
    }

    public function one(array $params = []): array
    {
        $this->statement->execute($params);

        return $this->statement->fetch(PDO::FETCH_ASSOC);
    }

    public function update(array $params = [])
    {
        $this->statement->execute($params);
    }
}