<?php
session_start();
require_once "../../connection/db_connection.php"; // Asegúrate de tener esta conexión configurada.

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Consulta para verificar usuario
    $sql = "SELECT * FROM usuarios WHERE username = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verificar la contraseña utilizando password_verify
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $username;
            $_SESSION['rol'] = $user['rol'];  // Asignar el rol a la sesión
            
            echo "Login exitoso";
        } else {
            echo "Usuario o contraseña incorrectos";
        }
        
    } else {
        echo "Usuario o contraseña incorrectos";
    }

    // Cerrar la declaración
    $stmt->close();
}
?>
