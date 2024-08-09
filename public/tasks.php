<!DOCTYPE html>
<html>
<head>
    <title>Lista de Tareas</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h2>Lista de Tareas</h2>


    <h3>Nueva Tarea</h3>
    <form id="new-task-form">
        <input type="text" id="title" placeholder="Título" required><br>
        <textarea id="description" placeholder="Descripción"></textarea><br>
        <button type="submit">Crear Tarea</button>
    </form>
    <div id="task-list">
    </div>
</div>

<script src="script.js"></script>
</body>
</html>
