<?php


require_once '../../config/config.php';
require_once '../../src/Database.php';
require_once '../Task.php';

// Iniciar sesión
session_start();

// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    http_response_code(401); // Unauthorized
    echo json_encode(array("status" => false, "message" => "No has iniciado sesión."));
    exit();
}

// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia de la clase Task
$task = new Task($db);

// Obtener los datos de la tarea (asumiendo que se envían como JSON desde JavaScript)
$data = json_decode(file_get_contents("php://input"));
var_dump($data);
// Asignar los valores a las propiedades de la tarea
$task->id = $data->id;
$task->user_id = $_SESSION['user_id'];
$task->status = $data->status;

// Actualizar la tarea
try {
    if ($task->updateStatus()) {
        // Estado actualizado con éxito
        echo json_encode(array("status" => true, "message" => "Estado de la tarea actualizado exitosamente."));
    } else {
        // Error al actualizar el estado
        echo json_encode(array("status" => false, "message" => "Error al actualizar el estado de la tarea."));
    }
} catch (PDOException $e) {
    // Capturar excepciones de la base de datos
    echo json_encode(array("status" => false, "message" => "Error en la base de datos: " . $e->getMessage()));
}
