<?php
require 'Task.php';
require 'Database.php';

// Configuración de la base de datos
$servername = "localhost"; // Cambia esto a tu configuración
$username = "DB_USER"; // Cambia esto a tu configuración
$password = "DB_PASS"; // Cambia esto a tu configuración
$dbname = "DB_NAME"; // Cambia esto a tu configuración

try {
    $db = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Error de conexión: ' . $e->getMessage()]);
    exit;
}

$task = new Task($db);

// Verificar el método de solicitud HTTP
$requestMethod = $_SERVER['REQUEST_METHOD'];

switch ($requestMethod) {
    case 'POST':
        // Crear una nueva tarea
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['user_id'], $data['title'], $data['description'], $data['status'])) {
            $task->user_id = $data['user_id'];
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->status = $data['status'];

            if ($task->create()) {
                echo json_encode(['success' => true, 'message' => 'Tarea creada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo crear la tarea']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
        break;

    case 'GET':
        // Leer tareas
        if (isset($_GET['user_id'])) {
            $user_id = $_GET['user_id'];
            $tasks = $task->read($user_id);
            echo json_encode(['success' => true, 'tasks' => $tasks]);
        } else {
            echo json_encode(['success' => false, 'message' => 'No se proporcionó user_id']);
        }
        break;

    case 'PUT':
        // Actualizar una tarea
        $data = json_decode(file_get_contents('php://input'), true);
        if (isset($data['id'], $data['user_id'], $data['title'], $data['description'], $data['status'])) {
            $task->id = $data['id'];
            $task->user_id = $data['user_id'];
            $task->title = $data['title'];
            $task->description = $data['description'];
            $task->status = $data['status'];

            if ($task->update()) {
                echo json_encode(['success' => true, 'message' => 'Tarea actualizada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo actualizar la tarea']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
        break;

    case 'DELETE':
        // Eliminar una tarea
        parse_str(file_get_contents('php://input'), $data);
        if (isset($data['id'], $data['user_id'])) {
            $task->id = $data['id'];
            $task->user_id = $data['user_id'];

            if ($task->delete()) {
                echo json_encode(['success' => true, 'message' => 'Tarea eliminada con éxito']);
            } else {
                echo json_encode(['success' => false, 'message' => 'No se pudo eliminar la tarea']);
            }
        } else {
            echo json_encode(['success' => false, 'message' => 'Datos incompletos']);
        }
        break;

    default:
        // Método no soportado
        echo json_encode(['success' => false, 'message' => 'Método no soportado']);
        break;
}

$db = null; // Cierra la conexión a la base de datos
?>
