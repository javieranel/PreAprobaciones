<?php
require_once "../connection/db_connection.php";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $id       = intval($_POST['id']);
    $username = trim($_POST['username']);
    $rol      = trim($_POST['rol']);
    $password = trim($_POST['password']);

    if (empty($username) || empty($rol)) {
        echo json_encode([
            "status" => false,
            "message" => "Usuario y rol son obligatorios"
        ]);
        exit;
    }

    // Si escribió una nueva contraseña
    if (!empty($password)) {

        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        $sql = "UPDATE usuarios
                SET username = ?, password = ?, rol = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $username,
            $passwordHash,
            $rol,
            $id
        );

    } else {

        $sql = "UPDATE usuarios
                SET username = ?, rol = ?
                WHERE id = ?";

        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param(
            $stmt,
            "ssi",
            $username,
            $rol,
            $id
        );
    }

    if (mysqli_stmt_execute($stmt)) {

        echo json_encode([
            "status" => true,
            "message" => "Usuario actualizado correctamente"
        ]);

    } else {

        echo json_encode([
            "status" => false,
            "message" => mysqli_error($con)
        ]);
    }
}