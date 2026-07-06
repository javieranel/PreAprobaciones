<?php

function registrarNotificacion($con, $documento_id, $accion)
{
    $sql = "
        INSERT INTO notificaciones
        (
            documento_id,
            accion
        )
        VALUES
        (
            ?,
            ?
        )
    ";

    $stmt = $con->prepare($sql);
    $stmt->bind_param("is", $documento_id, $accion);

    return $stmt->execute();
}