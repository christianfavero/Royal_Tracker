<?php
class Database {
    private $conn;

    public function __construct($host, $user, $pass, $db) {
        $this->conn = new mysqli($host, $user, $pass, $db);
        if ($this->conn->connect_error) 
            die("Errore connessione: " . $this->conn->connect_error);
    }

    public function getConnection() {
        return $this->conn;
    }
}
?>