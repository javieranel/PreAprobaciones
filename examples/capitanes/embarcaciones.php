<?php

require "../../connection/db_connection.php";


// Establecer cabecera para JSON
header('Content-Type: application/json');

// Validar y sanitizar el ID recibido
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

if (!$id) {
    echo json_encode(['error' => 'ID no válido']);
    exit;
}

// Obtener nombre de la empresa
$stmt = $con->prepare("SELECT nombre_empresa FROM documentos WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$stmt->bind_result($nombre_empresa);

if (!$stmt->fetch()) {
    $stmt->close();
    echo json_encode(['error' => 'Empresa no encontrada']);
    exit;
}
$stmt->close();

// Obtener barcazas asociadas a la empresa
$barcazas = [];
$stmt = $con->prepare("SELECT nombre FROM documentos WHERE nombre_empresa = ? AND categoria = 'Barcazas'");
$stmt->bind_param("s", $nombre_empresa);
$stmt->execute();
$result = $stmt->get_result();

while ($row = $result->fetch_assoc()) {
    $barcazas[] = $row['nombre'];
}

$stmt->close();
$con->close();

// Retornar respuesta en formato JSON
echo json_encode(['barcazas' => $barcazas]);



?>