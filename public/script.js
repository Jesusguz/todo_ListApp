document.addEventListener('DOMContentLoaded', function() {
    const taskList = document.getElementById('task-list');
    const newTaskForm = document.getElementById('new-task-form');
    const titleInput = document.getElementById('title');
    const descriptionInput = document.getElementById('description');
    const togglePasswordIcons = document.querySelectorAll('.toggle-password');

    togglePasswordIcons.forEach(icon => {
        icon.addEventListener('click', function() {
            const passwordInput = this.previousElementSibling; // Obtener el input de contraseña

            if (!passwordInput || passwordInput.type !== 'password' && passwordInput.type !== 'text') {
                console.warn('Elemento de entrada no encontrado o tipo no válido');
                return;
            }
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);

            // Alternar el ícono del ojo entre abierto y cerrado
            this.classList.toggle('fa-eye');
            this.classList.toggle('fa-eye-slash');
        });
    });

    // Cargar tareas existentes al cargar la página
    loadTasks();

    // Manejar envío del formulario de nueva tarea
    newTaskForm.addEventListener('submit', (event) => {
        event.preventDefault();
        createTask();
            loadTasks();
    });
    // Manejar cambio de estado en los selects
    taskList.addEventListener('change', function(event) {
        if (event.target.classList.contains('status-select')) {
            const taskId = event.target.dataset.taskId;
            const newStatus = event.target.value;
            updateTaskStatus(taskId, newStatus);
        }
    });

    // Función para cargar tareas desde el servidor

    function loadTasks() {
        fetch('../src/api/get_tasks.php')
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    taskList.innerHTML = '';
                    data.records.forEach(task => {
                        console.log(task)// Itera sobre data.records
                        addTaskToList(task);
                    });
                } else {
                    alert('Error al cargar las tareas: ' + data.message);
                }
            });
    }

    function addTaskToList(task) {
        const taskItem = document.createElement('div');
        taskItem.classList.add('task');
        taskItem.innerHTML = `
            <h3>${task.title}</h3>
            <p>${task.description}</p>
            <p>Estado: 
                <select class="status-select" data-task-id="${task.id}">
                    <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pendiente</option>
                    <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>En Progreso</option>
                    <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completada</option>
                    <option value="rejected" ${task.status === 'rejected' ? 'selected' : ''}>Rechazada</option>
                </select>
            </p>
            <button class="edit-btn" data-task-id="${task.id}">Editar</button>
            <button class="delete-btn" data-task-id="${task.id}">Eliminar</button>
        `;
        taskList.appendChild(taskItem);

        // Agregar event listeners para los botones de editar y eliminar (implementar más adelante)
        // ...
    }

    // Función para agregar una tarea a la lista (en el DOM)
    function addTaskToList(task) {
        const taskItem = document.createElement('div');
        taskItem.classList.add('task');
        taskItem.innerHTML = `
        <h3>${task.title}</h3>
            <p>${task.description}</p>
            <p>Estado: 
                <select class="status-select" data-task-id="${task.id}">
                    <option value="pending" ${task.status === 'pending' ? 'selected' : ''}>Pendiente</option>
                    <option value="in_progress" ${task.status === 'in_progress' ? 'selected' : ''}>En Progreso</option>
                    <option value="completed" ${task.status === 'completed' ? 'selected' : ''}>Completada</option>
                    <option value="rejected" ${task.status === 'rejected' ? 'selected' : ''}>Rechazada</option>
                </select>
            </p>
            <button class="edit-btn" data-task-id="${task.id}">Editar</button>
            <button class="delete-btn" data-task-id="${task.id}">Eliminar</button>
        `;
        taskList.appendChild(taskItem);
    }

    // Función para crear una nueva tarea (enviando datos al servidor)
    function createTask() {
        const data = {
            title: titleInput.value,
            description: descriptionInput.value
        };

        fetch('../src/api/create_task.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if
                (data.status) { // Verificar si la tarea se creó con éxito
                    addTaskToList(data); // Agregar la nueva tarea a la lista
                    titleInput.value = '';
                    descriptionInput.value = '';
                    loadTasks();
                } else {
                    alert('Error al crear la tarea: ' + data.message);
                }
            });
    }



    // Función para actualizar el estado de una tarea
    function updateTaskStatus(taskId, newStatus) {
        const data = {
            id: taskId,
            status: newStatus
        };

        fetch('../src/api/update_task_status.php', {
            method: 'POST',
            body: JSON.stringify(data),
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if
                (!data.status) {
                    alert('Error al actualizar el estado de la tarea: ' + data.message);
                } else {
                    // Puedes actualizar el estado visualmente en la interfaz si es necesario
                }
            });
    }

    // Manejar clic en los botones de editar y eliminar
    taskList.addEventListener('click', function(event) {
        if (event.target.classList.contains('edit-btn')) {
            const taskId = event.target.dataset.taskId;
            editTask(taskId);
        } else if (event.target.classList.contains('delete-btn')) {
            const taskId = event.target.dataset.taskId;
            deleteTask(taskId);
        }
    });

    // Función para editar una tarea (implementar la lógica de edición)
    function editTask(taskId) {
        fetch('../src/api/edit_task.php', {
            method: 'POST',
            body: JSON.stringify(updatedTaskData), // Datos actualizados de la tarea
            headers: {
                'Content-Type': 'application/json'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.status) {
                    loadTasks(); // Recargar las tareas después de editar
                } else {
                    alert('Error al editar la tarea: ' + data.message);
                }
            });
    }

    // Función para eliminar una tarea
    function deleteTask(taskId) {
        if (confirm('¿Estás seguro de que quieres eliminar esta tarea?')) {
            fetch('../src/api/delete_task.php', {
                method: 'POST',
                body: JSON.stringify({ id: taskId }),
                headers: {
                    'Content-Type': 'application/json'
                }
            })
                .then(response => response.json())
                .then(data => {
                    if (data.status)
                    {
                        loadTasks(); // Recargar las tareas después de eliminar
                    } else {
                        alert('Error al eliminar la tarea: ' + data.message);
                    }
                });
        }
    }
});
