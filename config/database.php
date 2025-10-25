<?php
// config/database.php

class Database {
    private $host;
    private $db_name;
    private $username;
    private $password;
    private $port;
    public $conn;

    public function __construct() {
        // Use PostgreSQL from Replit environment
        $database_url = getenv('DATABASE_URL');
        
        if ($database_url) {
            $parts = parse_url($database_url);
            $this->host = $parts['host'];
            $this->port = $parts['port'] ?? 5432;
            $this->db_name = ltrim($parts['path'], '/');
            $this->username = $parts['user'];
            $this->password = $parts['pass'] ?? '';
        } else {
            // Fallback to local config
            $this->host = getenv('PGHOST') ?? 'localhost';
            $this->db_name = getenv('PGDATABASE') ?? 'wc_clone';
            $this->username = getenv('PGUSER') ?? 'postgres';
            $this->password = getenv('PGPASSWORD') ?? '';
            $this->port = getenv('PGPORT') ?? 5432;
        }
    }

    public function getConnection() {
        $this->conn = null;
        try {
            $dsn = "pgsql:host=" . $this->host . ";port=" . $this->port . ";dbname=" . $this->db_name;
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            die("Connection error: " . $exception->getMessage());
        }
        return $this->conn;
    }
}
?>
