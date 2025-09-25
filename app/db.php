<?php
/**
 * Database Connection and Query Builder
 * Provides PDO wrapper with prepared statements
 */

class Database {
    private static ?Database $instance = null;
    private PDO $connection;
    private array $config;
    
    private function __construct(array $config) {
        $this->config = $config;
        $this->connect();
    }
    
    private function connect(): void {
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $this->config['host'],
            $this->config['port'],
            $this->config['name'],
            $this->config['charset']
        );
        
        try {
            $this->connection = new PDO(
                $dsn,
                $this->config['username'],
                $this->config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                    PDO::ATTR_EMULATE_PREPARES => false,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->config['charset']} COLLATE {$this->config['collation']}"
                ]
            );
        } catch (PDOException $e) {
            throw new Exception('Database connection failed: ' . $e->getMessage());
        }
    }
    
    public static function getInstance(array $config = null): Database {
        if (self::$instance === null) {
            if ($config === null) {
                $config = require __DIR__ . '/config.php';
                $config = $config['database'];
            }
            self::$instance = new self($config);
        }
        return self::$instance;
    }
    
    public function getConnection(): PDO {
        return $this->connection;
    }
    
    public function query(string $sql, array $params = []): PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    
    public function select(string $sql, array $params = []): array {
        return $this->query($sql, $params)->fetchAll();
    }
    
    public function selectOne(string $sql, array $params = []): ?array {
        $result = $this->query($sql, $params)->fetch();
        return $result ?: null;
    }
    
    public function insert(string $table, array $data): int {
        $columns = array_keys($data);
        $values = array_map(fn($col) => ":$col", $columns);
        
        $sql = sprintf(
            "INSERT INTO %s (%s) VALUES (%s)",
            $table,
            implode(', ', $columns),
            implode(', ', $values)
        );
        
        $this->query($sql, $data);
        return (int) $this->connection->lastInsertId();
    }
    
    public function update(string $table, array $data, string $where, array $whereParams = []): int {
        $sets = array_map(fn($col) => "$col = :$col", array_keys($data));
        
        $sql = sprintf(
            "UPDATE %s SET %s WHERE %s",
            $table,
            implode(', ', $sets),
            $where
        );
        
        $stmt = $this->query($sql, array_merge($data, $whereParams));
        return $stmt->rowCount();
    }
    
    public function delete(string $table, string $where, array $params = []): int {
        $sql = "DELETE FROM $table WHERE $where";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }
    
    public function transaction(callable $callback): mixed {
        $this->connection->beginTransaction();
        try {
            $result = $callback($this);
            $this->connection->commit();
            return $result;
        } catch (Exception $e) {
            $this->connection->rollBack();
            throw $e;
        }
    }
    
    public function tableExists(string $table): bool {
        $sql = "SHOW TABLES LIKE :table";
        $result = $this->selectOne($sql, ['table' => $table]);
        return $result !== null;
    }
}