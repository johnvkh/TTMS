<?php


class DatabaseConfig {

    private $servername = "163.44.198.58";
    private $username = "cp207321_khoun";
    private $password = "1qazZAQ!2022";
    private $databaseName = "cp207321_khounkham";

//    private $servername = "109.106.254.201";
//    private $username = "u446108715_weroot";
//    private $password = "1qazZAQ!";
//    private $databaseName = "u446108715_uat";

    public function connection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->servername. ";dbname=" . $this->databaseName . ";charset=utf8", $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }

    public function disconnection() {
        try {
            return die();
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
        }
        return $this->conn;
    }

}
