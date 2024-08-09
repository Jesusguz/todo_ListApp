<?php

// Incluir archivos necesarios
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

// Obtener el ID de la tarea a eliminar desde la petición AJAX
$data = json_decode(file_get_contents("php://input"));
$task->id = $data->id;
$task->user_id = $_SESSION['user_id']; // Asignar el ID del usuario de la sesión

// Eliminar la tarea
if ($task->delete()) {
    // Tarea eliminada con éxito
    echo json_encode(array("status" => true, "message" => "Tarea eliminada exitosamente."));
} else {
    // Error al eliminar la tarea
    echo json_encode(array("status" => false, "message" => "Error al eliminar la tarea."));
}
