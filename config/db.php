<?php
class Database {
    private $host = "localhost";
    private $dbname = "inventario_ventas";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        $this->conn = null;

        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset=utf8mb4";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error de conexión: " . $e->getMessage());
        }

        return $this->conn;
    }
}
