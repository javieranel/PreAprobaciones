<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

require_once "../../connection/db_connection.php";
require_once "../notification/Email_Notification.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Obtener los datos del formulario
    $data = json_decode(file_get_contents("php://input"), true);

    $nombre = $data["nombre"];
    $tipo_barcaza = $data["tipo_barcaza"];
    $empresa = $data["empresa"];
    $categoria = $data["categoria"];

    // Consulta SQL preparada para la barcaza
    $sql_barcaza = "INSERT INTO documentos (
                        nombre,
                        tipo_barcaza,
                        nombre_empresa,
                        categoria
                    ) VALUES (?, ?, ?, ?)";

    $stmt_barcaza = $con->prepare($sql_barcaza);

    if (!$stmt_barcaza) {
        die("Error prepare: " . $con->error);
    }

    $stmt_barcaza->bind_param(
        "ssss",
        $nombre,
        $tipo_barcaza,
        $empresa,
        $categoria
    );

    if ($stmt_barcaza->execute()) {

        echo "INSERT OK<br>";

        $idDocumento = $con->insert_id;

        echo "ID DOCUMENTO: " . $idDocumento . "<br>";

        // Verificar si existe la función
        if (function_exists('enviarCorreoNuevoRegistro')) {
            enviarCorreoNuevoRegistro($con, $idDocumento);
        }

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

            echo "Nueva barcaza agregada correctamente.";
        } else {

            echo "Error al crear notificación: "
                . $stmt_notificacion->error;
        }
    } else {

        echo "ERROR INSERT: " . $stmt_barcaza->error;
    }

    $stmt_barcaza->close();
} else {

    echo "Datos no proporcionados o método de solicitud incorrecto";
}

$con->close();
