<?php


require_once '../../config/config.php';
require_once '../../src/Database.php';
require_once '../Task.php';

session_start();
// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia de la clase Task
$task = new Task($db);

// Obtener los datos de la tarea (asumiendo que se envían como JSON desde JavaScript)
$data = json_decode(file_get_contents("php://input"));
var_dump($data,$_SESSION['user_id']);
// Asignar los valores a las propiedades de la tarea
$task->user_id = $_SESSION['user_id']; // Obtener el ID del usuario de la sesión
$task->title = $data->title;
$task->description = $data->description;
$task->status = 'pending'; // Estado inicial de la tarea

// Crear la tarea
if ($task->create()) {
    // Tarea creada con éxito
    $task_arr = array(
        "status" => true,
        "message" => "Tarea creada exitosamente.",
        "id" => $task->id,
        "title" => $task->title,
        "description" => $task->description,
        "status" => $task->status
    );
} else {
    // Error al crear la tarea
    $task_arr = array(
        "status" => false,
        "message" => "Error al crear la tarea."
    );
}

// Enviar respuesta en formato JSON
header('Content-Type: application/json');
echo json_encode($task_arr);
