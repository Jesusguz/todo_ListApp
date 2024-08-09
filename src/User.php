<?php
class User {
    private $conn;
    private $table_name = "users";

    public $id;
    public $username;
    public $password;

    public function __construct($db) {
        $this->conn = $db;
    }

    function register() {
        $query = "INSERT INTO " . $this->table_name . " SET username=:username, password_hash=:password_hash";

        $stmt = $this->conn->prepare($query);

        $this->username=htmlspecialchars(strip_tags($this->username));
        $this->password=htmlspecialchars(strip_tags($this->password));

        $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

        $stmt->bindParam(":username", $this->username);
        $stmt->bindParam(":password_hash", $password_hash);

        if($stmt->execute()){
            return true;
        }

        return false;
    }

    function login() {
        $query = "SELECT id, username, password_hash FROM " . $this->table_name . " WHERE username = ? LIMIT 0,1";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->username);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row && password_verify($this->password, $row['password_hash'])) {
            $this->id = $row['id'];
            $this->username = $row['username'];
            return true;
        }
        return false;
    }
}
