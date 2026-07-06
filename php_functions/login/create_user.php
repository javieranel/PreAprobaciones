<?php
session_start();
require_once "../../connection/db_connection.php"; // Asegúrate de tener esta conexión configurada.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'] ?? null;
    $password = $_POST['password'] ?? null;
    $rol = $_POST['rol'] ?? null; // Evita error si 'rol' no está presente

    // Validar que los datos no estén vacíos
    if (empty($username) || empty($password) || empty($rol)) {
        echo "Por favor, completa todos los campos.";
        exit;
    }

    // Encriptar la contraseña
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Verificar si el usuario ya existe
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        echo "El usuario ya existe.";
        exit;
    }

    // Insertar el nuevo usuario en la base de datos
    $sql = "INSERT INTO usuarios (username, password, rol) VALUES (?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("sss", $username, $hashed_password, $rol);
    
    if ($stmt->execute()) {
        echo "Usuario creado exitosamente";
    } else {
        echo "Error al crear el usuario. Intenta nuevamente.";
    }
}

?>
