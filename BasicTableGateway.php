<?php

abstract class BasicTableGateway
{
    protected $db;
    protected $table;
    protected $fields;
    protected $primary = 'id';

    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /* Generic methods */
    public function sql(string $sql, array $params): \PDOStatement
    {
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }
    public function listBySql(string $sql, array $params = [], int $mode = PDO::FETCH_ASSOC): array
    {
        return $this->sql($sql, $params)->fetchAll($mode);
    }
    public function getBySql(string $sql, array $params = [], int $mode = PDO::FETCH_ASSOC): array|bool
    {
        return $this->sql($sql, $params)->fetch($mode);
    }
    public function getByField(string $fieldName, string $value, int $mode = PDO::FETCH_ASSOC): array|bool
    {
        $this->validate([$fieldName => $value]);
        $sql = "SELECT * FROM `$this->table` WHERE `$fieldName`=?";
        return $this->getBySql($sql, [$value], $mode);
    }

    /* CRUD methods */
    public function create($data): int
    {
        $fields = $this->makeFieldList($data);
        $placeholders = str_repeat('?,', count($data) - 1) . '?';

        $sql = "INSERT INTO `$this->table` ($fields) VALUES ($placeholders)";
        $this->sql($sql,array_values($data));

        return $this->db->lastInsertId();
    }
    public function read($id): array|bool
    {
        return $this->getByField($this->primary, $id);
    }
    public function update(array $data, int $id): void
    {
        [$params, $set] = $this->makeSET($data);
        $params[] = $id;
        $sql = "UPDATE `$this->table` SET $set WHERE `$this->primary`=?";
        $this->db->prepare($sql)->execute($params);
    }
    public function delete($id)
    {
        $sql = "DELETE FROM `$this->table` WHERE `$this->primary`=?";
        $this->sql($sql,[$id]);
    }

    /* Service methods */
    protected function validate($data)
    {
        $diff = array_diff(array_keys($data), [$this->primary, ...$this->fields]);
        if ($diff) {
            throw new \InvalidArgumentException("Unknown field(s): ". implode($diff));
        }
    }
    protected function makeFieldList($data)
    {
        $this->validate($data);
        return '`'.implode("`,`", array_keys($data)).'`';
    }
    protected function makeSET($data)
    {
        $this->validate($data);
        $params = [];
        $set = "";
        foreach($data as $key => $value)
        {
            $set .= ($set ? "," : "") . "`$key` = ?";
            $params[] = $value;
        }
        return [$params,$set];
    }
}
