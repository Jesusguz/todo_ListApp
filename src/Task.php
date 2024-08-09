<?php
class Task
{
    private $conn;
    private $table_name = "tasks";

    public $id;
    public $user_id;
    public $title;
    public $description;
    public $status;

    public function __construct($db)
    {
        $this->conn = $db;
    }

    function create() {
        $sql = "INSERT INTO $this->table_name (user_id, title, description, status) VALUES (?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->user_id, $this->title, $this->description, $this->status]);
        return $stmt->rowCount() > 0; // Devuelve true si se insertó la tarea
    }

    function read($user_id) {
        $sql = "SELECT * FROM $this->table_name WHERE user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Devuelve un array con las tareas
    }

    function update() {
        $sql = "UPDATE $this->table_name SET title = ?, description = ?, status = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->title, $this->description, $this->status, $this->id, $this->user_id]);
        return $stmt->rowCount() > 0; // Devuelve true si se actualizó la tarea
    }
    function updateStatus() {
        $sql = "UPDATE $this->table_name SET status = ? WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->status, $this->id, $this->user_id]);
        return true; // Devuelve true si la consulta se ejecuta sin errores
    }

    function delete() {
        $sql = "DELETE FROM $this->table_name WHERE id = ? AND user_id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$this->id, $this->user_id]);
        return $stmt->rowCount() > 0; // Devuelve true si se eliminó la tarea
    }
}