<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../connection/db_connection.php";
require_once "../notification/Email_Notification.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener datos enviados por AJAX
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $data["nombre"];
    $empresa = $data["empresa"];
    $categoria = $data["categoria"];

    // Insertar inspector
    $sql = "
        INSERT INTO documentos
        (
            nombre,
            nombre_empresa,
            categoria
        )
        VALUES
        (
            ?,
            ?,
            ?
        )
    ";

    $stmt = $con->prepare($sql);

    if (!$stmt) {
        die("Error prepare: " . $con->error);
    }

    $stmt->bind_param(
        "sss",
        $nombre,
        $empresa,
        $categoria
    );

    if ($stmt->execute()) {

        $idDocumento = $con->insert_id;

        // Enviar correo
        if (function_exists('enviarCorreoNuevoRegistro')) {

            enviarCorreoNuevoRegistro(
                $con,
                $idDocumento
            );

        }

        // Registrar notificación
        $sql_notificacion = "
            INSERT INTO notificaciones
            (
                documento_id,
                accion
            )
            VALUES
            (
                ?,
                'NUEVO'
            )
        ";

        $stmt_notificacion = $con->prepare($sql_notificacion);

        $stmt_notificacion->bind_param(
            "i",
            $idDocumento
        );

        if ($stmt_notificacion->execute()) {

            echo "Nuevo Inspector agregado correctamente.";

        } else {

            echo "Error al crear notificación: "
                . $stmt_notificacion->error;
        }

        $stmt_notificacion->close();

    } else {

        echo "Error al agregar Inspector: "
            . $stmt->error;
    }

    $stmt->close();

} else {

    echo "Datos no proporcionados o método incorrecto.";

}

$con->close();

?>