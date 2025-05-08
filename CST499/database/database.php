<?php
require_once __DIR__ . '/../config.php';

class Database {
    private $connection;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->connection = new mysqli(DBHOST, DBUSER, DBPASS, DBNAME);

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }
    }

    public function executeSelectQuery($sql) {
        $result = $this->connection->query($sql);

        if ($result === FALSE) {
            throw new Exception("Error executing query: " . $this->connection->error);
        }

        return $result;        
    }

    public function executeSafeSelectQuery($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
    
        if ($stmt === FALSE) {
            throw new Exception("Error preparing statement: " . $this->connection->error);
        }
    
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); 
            $stmt->bind_param($types, ...$params);
        }
    
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        
        $result = $stmt->get_result();
    
        if ($result === FALSE) {
            throw new Exception("Error getting result: " . $stmt->error);
        }
    
        $data = $result->fetch_all(MYSQLI_ASSOC);
    
        $stmt->close();
    
        return $data;
    }

    public function executeSafeQuery($sql, $params = []) {
        $stmt = $this->connection->prepare($sql);
    
        if ($stmt === FALSE) {
            throw new Exception("Error preparing statement: " . $this->connection->error);
        }
    
        if (!empty($params)) {
            $types = str_repeat('s', count($params)); 
            $stmt->bind_param($types, ...$params);
        }
    
        if (!$stmt->execute()) {
            throw new Exception("Error executing statement: " . $stmt->error);
        }
        
        $stmt->close();
    }


    public function executeQuery($sql) {
        $result = $this->connection->query($sql);
        if ($result === FALSE) {
            throw new Exception("Error executing query: " . $this->connection->error);
        }
        return $result;
    }


    public function escape_string($string) {
        return $this->connection->real_escape_string($string);
    }

    public function close() {
        $this->connection->close();
    }

    public function beginTransaction() {
        $this->connection->begin_transaction();
    }
    
    public function commitTransaction() {
        $this->connection->commit();
    }

    public function rollbackTransaction() {
        $this->connection->rollback();
    }
}