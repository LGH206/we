<?php
class Setting {
    private $conn;
    private $table_name = "settings";

    public function __construct($db) {
        $this->conn = $db;
    }

    public function get($key) {
        $query = "SELECT value FROM " . $this->table_name . " WHERE `key` = :key LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':key', $key);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ? $row['value'] : null;
    }

    public function set($key, $value) {
        $query = "INSERT INTO " . $this->table_name . " (`key`, `value`) 
                  VALUES (:key, :value) 
                  ON DUPLICATE KEY UPDATE `value` = :value";
        $stmt = $this->conn->prepare($query);
        $stmt->bindValue(':key', $key);
        $stmt->bindValue(':value', $value);
        return $stmt->execute();
    }
}
?>