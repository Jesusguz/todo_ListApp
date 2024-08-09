<!DOCTYPE html>
<html>
<head>
    <title>Inicio de Sesión</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
<div class="container">
    <h2>Inicio de Sesión</h2>
    <form action="register.php" method="post">
        <input type="text" name="username" placeholder="Nombre de usuario" required><br>
        <div class="password-container">
            <input type="password" id="password" name="password" placeholder="Contraseña" required>
            <i class="fas fa-eye toggle-password"></i>
        </div>
        <button type="submit">Iniciar Sesión</button>
    </form>
    <p>¿No tienes cuenta? <a href="index.php">Registrarse</a></p>
</div>
<script src="script.js"></script>
</body>
</html>
