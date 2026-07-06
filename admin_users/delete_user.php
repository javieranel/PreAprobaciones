<?php
require_once "../connection/db_connection.php";

$id = intval($_POST['id']);

$sql = "DELETE FROM usuarios WHERE id = ?";

$stmt = mysqli_prepare($con, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "i",
    $id
);

if (mysqli_stmt_execute($stmt)) {

    echo json_encode([
        "status" => true
    ]);

} else {

    echo json_encode([
        "status" => false
    ]);

}