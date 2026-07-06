<?php
require_once "../../connection/db_connection.php";

header("Content-Type: application/json");

if (!isset($_POST['id'])) {
    echo json_encode(["error" => "ID no recibido"]);
    exit;
}

$id = intval($_POST['id']);
$response = [];

// Verificar si el ID existe
$checkSql = "SELECT COUNT(*) FROM notificaciones WHERE id = ?";
$checkStmt = $con->prepare($checkSql);
$checkStmt->bind_param("i", $id);
$checkStmt->execute();
$checkStmt->bind_result($count);
$checkStmt->fetch();
$checkStmt->close();

if ($count === 0) {
    echo json_encode(["error" => "ID no encontrado"]);
    exit;
}

$sql = "UPDATE notificaciones SET visto = 1 WHERE id = ?";
$stmt = $con->prepare($sql);

if (!$stmt) {
    echo json_encode(["error" => "Error en la preparación de la consulta", "detalle" => $con->error]);
    exit;
}

$stmt->bind_param("i", $id);

if ($stmt->execute()) {
    $response["success"] = true;
    $response["message"] = "Notificación marcada como vista";
} else {
    error_log("Error en SQL: " . $stmt->error);
    $response["error"] = "Error en SQL";
    $response["detalle"] = $stmt->error;
}

$stmt->close();
$con->close();

echo json_encode($response);
?>



