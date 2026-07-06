<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrar Usuarios</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        body {
            background-image: url('./assets/image/imagen_logo_.jpeg');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            color: #333;
            padding: 20px 0;
        }

        .admin-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
            padding: 30px;
            margin-top: 20px;
        }

        .logo-container {
            text-align: center;
            margin-bottom: 20px;
        }

        .logo-container img {
            width: 180px;
            height: auto;
        }

        .btn-custom {
            background-color: #f79c42;
            border: none;
            font-weight: bold;
            color: white;
        }

        .btn-custom:hover {
            background-color: #ff7f50;
            color: white;
        }

        .table-responsive {
            background: #fff;
            border-radius: 8px;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="container admin-container">
        <div class="logo-container">
            <img src="../assets/image/logo_nombre.png" alt="Logo">
        </div>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 style="color: #007bff; font-weight: bold;">Administración de Usuarios</h2>


            <button class="btn btn-primary" onclick="window.location.href='../examples/dashboard.php'" >
                <i class="bi bi-house"></i> Incio
            </button>

            <button class="btn btn-custom" data-bs-toggle="modal" data-bs-target="#createModal" onclick="window.location.href='./create_user.html'">
                <i class="bi bi-person-plus-fill"></i> Nuevo Usuario
            </button>



        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Usuario</th>
                        <th>Rol / Categoría</th>
                        <th class="text-center">Acciones</th>
                    </tr>
                </thead>
                <?php
                require_once "../connection/db_connection.php";

                $sql = "SELECT * FROM usuarios ORDER BY id ASC";
                $result = mysqli_query($con, $sql);
                ?>

                <tbody id="userTableBody">

                    <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?= $row['id']; ?></td>
                            <td><?= $row['username']; ?></td>
                            <td><?= $row['rol']; ?></td>
                            <td class="text-center">
                                <button
                                    class="btn btn-sm btn-warning"
                                    onclick="abrirEditar(
                    <?= $row['id']; ?>,
                    '<?= $row['username']; ?>',
                    '<?= $row['rol']; ?>'
                )">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </button>

                                <button
                                    class="btn btn-sm btn-danger"
                                    onclick="eliminarUsuario(<?= $row['id']; ?>)">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </td>
                        </tr>
                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title" id="editModalLabel"><i class="bi bi-pencil-square"></i> Editar Usuario</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <input type="hidden" id="editUserId">

                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Usuario</label>
                            <input type="text" class="form-control" id="editUsername" required>
                        </div>


                        <div class="mb-3">
                            <label for="editPassword" class="form-label">Nueva Contraseña (Dejar en blanco para no cambiar)</label>
                            <input type="password" class="form-control" id="editPassword" placeholder="********">
                        </div>

                        <div class="mb-3">
                            <label for="editRol" class="form-label">Categoría / Rol</label>
                            <select id="editRol" class="form-select" required>
                                <option value="Administrador">Administrador</option>
                                <option value="Gerente General">Gerente General</option>
                                <option value="Operaciones">Operaciones</option>
                                <option value="HSE">HSE</option>
                                <option value="Garita de Seguridad">Garita de Seguridad</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                    <button type="button" class="btn btn-custom" onclick="guardarCambiosUsuario()">Guardar Cambios</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="./assets/js/ajax.js"></script>

    <script>
        // Función para capturar los datos actuales de la fila y pasarlos al Modal
        function abrirEditar(id, username, rol) {
            $('#editUserId').val(id);
            $('#editUsername').val(username);
            $('#editRol').val(rol);
            $('#editPassword').val(''); // Se limpia el campo de contraseña por seguridad

            // Abre el modal de Bootstrap de forma manual
            var myModal = new bootstrap.Modal(document.getElementById('editModal'));
            myModal.show();
        }

        // Función que conectarás con tu archivo AJAX para actualizar la BD
        function guardarCambiosUsuario() {

    const id = $('#editUserId').val();
    const username = $('#editUsername').val();
    const password = $('#editPassword').val();
    const rol = $('#editRol').val();

    $.ajax({
        url: '../admin_users/update_user.php',
        type: 'POST',
        dataType: 'json',
        data: {
            id: id,
            username: username,
            password: password,
            rol: rol
        },
        success: function(response) {

            if (response.status) {

                Swal.fire({
                    icon: 'success',
                    title: 'Correcto',
                    text: response.message
                }).then(() => {
                    location.reload();
                });

            } else {

                Swal.fire(
                    'Error',
                    response.message,
                    'error'
                );

            }
        },
        error: function() {

            Swal.fire(
                'Error',
                'No se pudo conectar con el servidor',
                'error'
            );

        }
    });
}

        function eliminarUsuario(id) {

    Swal.fire({
        title: '¿Eliminar usuario?',
        text: 'Esta acción no se puede revertir',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sí, eliminar'
    }).then((result) => {

        if (result.isConfirmed) {

            $.ajax({
                url: '../admin_users/delete_user.php',
                type: 'POST',
                dataType: 'json',
                data: {
                    id: id
                },
                success: function(response) {

                    if (response.status) {

                        Swal.fire(
                            'Eliminado',
                            'Usuario eliminado correctamente',
                            'success'
                        ).then(() => {
                            location.reload();
                        });

                    } else {

                        Swal.fire(
                            'Error',
                            'No se pudo eliminar',
                            'error'
                        );

                    }

                }
            });

        }

    });
}
    </script>
</body>

</html>