<?php

namespace Bdt\Example;

use Doctrine\DBAL\Connection;
use JsonSerializable;

/**
 * @phpstan-consistent-constructor
 */
class BaseModel implements JsonSerializable
{
    static protected Connection $connection;

    /** @var array<string, mixed> */
    protected array $data = [];

    public static function setDefaultConnection(Connection $connection): void
    {
        self::$connection = $connection;
    }

    public static function getDefaultConnection(): Connection
    {
        return self::$connection;
    }

    /** @return false|static */
    public static function findById(int $id): bool|static
    {
        $stmt = self::$connection->prepare('SELECT * FROM '.static::getName().' WHERE id = ?');
        $stmt->bindValue(1, $id);
        $result = $stmt->executeQuery();

        $row = $result->fetchAssociative();
        if (is_array($row)) {
            return new static($row);
        }
        return false;
    }

    /** @return array<static> */
    public static function findAll(): array
    {
        $stmt = self::$connection->executeQuery('SELECT * FROM '.static::getName());
        $rows = $stmt->fetchAllAssociative();
        $objects = [];
        $class = static::class;
        foreach ($rows as $row) {
            $objects[] = new static($row);
        }
        return $objects;
    }

    /**
     * Model constructor.
     * @param array<string, mixed> $data
     */
    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function save(): void
    {
       // error_log("Updating post with ID: " . $this->data['id']);
        if (isset($this->data['id'])) {
            self::$connection->update(static::getName(), $this->data, ['id' => $this->data['id']]);
        } else {
            self::$connection->insert(static::getName(), $this->data);
            $this->data['id'] = self::$connection->lastInsertId();
        }
    }

    public function delete(): void
    {
        self::$connection->delete(static::getName(), ['id' => $this->data['id']]);
    }

    public function set(string $k , mixed $v): void
    {
        $this->data[$k] = $v;
    }

    public function get(string $k): mixed
    {
        return isset($this->data[$k]) ? $this->data[$k] : null;
    }

    public function has(string $k): bool
    {
        return isset($this->data[$k]);
    }

    /** @return array<string, mixed> */
    public function jsonSerialize(): array
    {
        return $this->data;
    }

    protected static function getName(): string
    {
        return str_replace("app\models\\", "", strtolower(static::class).'s');
    }
    public function getId(): int
    {
        return $this->get('id'); 
    }
}
