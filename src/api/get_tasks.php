<?php

// Incluir archivos necesarios
require_once '../../config/config.php';
require_once '../../src/Database.php';
require_once '../Task.php';

// Iniciar sesión
session_start();
//var_dump($_SESSION['user_id']);
// Verificar si el usuario ha iniciado sesión
if (!isset($_SESSION['user_id'])) {
    // El usuario no ha iniciado sesión, devolver un error
    http_response_code(401); // Unauthorized
    echo json_encode(array("status" => false, "message" => "No has iniciado sesión."));
    exit();
}

// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia de la clase Task
$task = new Task($db);

// Obtener las tareas del usuario actual
$stmt = $task->read($_SESSION['user_id']);
//var_dump($stmt);
$tasks = $stmt;

if (!empty($tasks)) {
   // var_dump($tasks);
    $tasks_arr = array();

    // Iterar sobre las tareas y agregarlas al array
    foreach ($tasks as $row) {
        $task_item = array(
            "id" => $row['id'],
            "title" => $row['title'],
            "description" => html_entity_decode($row['description']),
            "status" => $row['status']
        );
        array_push($tasks_arr, $task_item);
    }
   // var_dump($task_item);

    // Establecer el código de respuesta HTTP a 200 (OK)
    http_response_code(200);

    // Devolver las tareas en formato JSON
    echo json_encode(array("status" => true, "records" => $tasks_arr));
} else {
    // No se encontraron tareas, devolver un mensaje adecuado
    http_response_code(200); // OK, pero sin tareas
    echo json_encode(array("status" => false, "message" => "No se encontraron tareas."));
}