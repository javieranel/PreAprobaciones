<?php
require_once "../../connection/db_connection.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST)) {
    $id = isset($_POST["id"]) ? intval($_POST["id"]) : null;
    $nombre = $_POST["nombre"] ?? null;
    $nombre_empresa = $_POST["nombre_empresa"] ?? null;
    $exencion_acp = $_POST["exencion_acp"] ?? null;
    $licencia_amp = $_POST["licencia_amp"] ?? null;
    $max_grt = $_POST["max_grt"] ?? null;
    $embarcaciones = $_POST["embarcaciones"] ?? null;

    

    if ($id !== null) {
        $stmt = $con->prepare("
            UPDATE documentos 
            SET nombre = ?, 
                nombre_empresa = ?,
                exencion_acp = ?, 
                licencia_amp = ?, 
                max_grt = ?, 
                embarcaciones = ?
            WHERE id = ?
        ");

        $stmt->bind_param(
            "ssssssi", 
            $nombre, 
            $nombre_empresa, 
            $exencion_acp, 
            $licencia_amp, 
            $max_grt, 
            $embarcaciones, 
            $id
        );

        if ($stmt->execute()) {
            echo json_encode([
                "status" => "success",
                "message" => "Datos actualizados con éxito"
            ]);
        } else {
            echo json_encode([
                "status" => "error",
                "message" => "Error al actualizar: " . $stmt->error
            ]);
        }

        $stmt->close();
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "ID no válido o no proporcionado"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Método incorrecto o datos vacíos"
    ]);
}


$con->close();

?>