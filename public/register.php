<?php
// Incluir archivos necesarios
require_once '../config/config.php';
require_once '../src/Database.php';
require_once '../src/User.php';

// Obtener la conexión a la base de datos
$database = new Database();
$db = $database->getConnection();

// Crear una instancia de la clase User
$user = new User($db);

// Obtener los datos del formulario
$user->username = $_POST['username'];
$user->password = $_POST['password'];

// Registrar al usuario
if ($user->register()) {
    // Registro exitoso
    echo "<script>alert('Registro exitoso. Ahora puedes iniciar sesión.'); window.location.href = 'login.php';</script>";
    $user->login(); // Iniciar sesión después del registro
    session_start(); // Iniciar sesión para guardar el ID del usuario
    $_SESSION['user_id'] = $user->id; // Guardar el ID en la sesión
    header("Location: tasks.php"); // Redirigir a tasks.php
    exit();
} else {
    // Error en el registro
    echo "<script>alert('Error en el registro. Inténtalo de nuevo.'); window.location.href = 'index.php';</script>";
}
if ($user->login()) {
    // Inicio de sesión exitoso
    session_start(); // Iniciar sesión
    $_SESSION['user_id'] = $user->id; // Guardar el ID en la sesión
    header("Location: tasks.php"); // Redirigir a tasks.php
    exit();
} else {
    // Error en el inicio de sesión
    echo "<script>alert('Error en el inicio de sesión. Verifica tus credenciales.'); window.location.href = 'login.php';</script>";
}
