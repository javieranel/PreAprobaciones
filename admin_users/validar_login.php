<?php
session_start();
require_once "../connection/db_connection.php";

$username = $_POST['username'];
$password = $_POST['password'];

$sql = "SELECT * FROM usuarios WHERE username = ?";
$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param($stmt, "s", $username);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);

if ($row = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $row['password'])) {

        $_SESSION['id_usuario'] = $row['id'];
        $_SESSION['usuario'] = $row['username'];
        $_SESSION['rol'] = $row['rol'];

        echo "Login exitoso";

    } else {

        echo "Contraseña incorrecta";
    }

} else {

    echo "Usuario no encontrado";
}