<?php
require_once "../../connection/db_connection.php";
require_once "../notification/Email_Notification.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $id = isset($_POST["id"]) ? intval($_POST["id"]) : 0;

    if ($id > 0) {

        $sql = "
            SELECT
                nombre,
                categoria,
                nombre_empresa
            FROM documentos
            WHERE id = ?
        ";

        $stmt = $con->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $resultado = $stmt->get_result();

        if ($documento = $resultado->fetch_assoc()) {

            $nombre = $documento['nombre'];
            $categoria = $documento['categoria'];
            $empresa = $documento['nombre_empresa'];

            $sqlDelete = "
                DELETE FROM documentos
                WHERE id = ?
            ";

            $stmtDelete = $con->prepare($sqlDelete);
            $stmtDelete->bind_param("i", $id);

            if ($stmtDelete->execute()) {

                enviarCorreoEliminacion(
                    $nombre,
                    $categoria,
                    $empresa
                );

                echo "OK";

            } else {

                echo "Error al eliminar: " . $stmtDelete->error;

            }

            $stmtDelete->close();

        } else {

            echo "Documento no encontrado";

        }

        $stmt->close();

    } else {

        echo "ID inválido";

    }

} else {

    echo "Método no permitido";

}

$con->close();
?>