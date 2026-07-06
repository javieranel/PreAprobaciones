<?php
// Conexión a la base de datos
require_once "../connection/db_connection.php"; // Debe definir $con (mysqli)

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $categoria = trim($_POST['categoria'] ?? '');
    $compania  = trim($_POST['compania'] ?? '');

    if ($categoria === '' || $compania === '') {
        echo "<script>
                alert('Por favor, completa todos los campos.');
                window.history.back();
              </script>";
        exit;
    }

    // Validar que no exista la misma combinación categoria+compania (case insensitive)
    $check = $con->prepare("SELECT id FROM categories WHERE LOWER(categoria) = LOWER(?) AND LOWER(compania) = LOWER(?) LIMIT 1");
    $check->bind_param("ss", $categoria, $compania);
    $check->execute();
    $check->store_result();

    if ($check->num_rows > 0) {
        echo "<script>
                alert('Esta compañía ya está registrada en esta categoría.');
                window.history.back();
              </script>";
        $check->close();
        exit;
    }
    $check->close();

    // Insertar
    $stmt = $con->prepare("INSERT INTO categories (categoria, compania) VALUES (?, ?)");
    if ($stmt === false) {
        echo "<script>
                alert('Error en la preparación de la consulta.');
                window.history.back();
              </script>";
        exit;
    }
    $stmt->bind_param("ss", $categoria, $compania);
    $success = $stmt->execute();
    $stmt->close();

    if ($success) {
        echo "<script>
                alert('Registro guardado correctamente.');
                window.location.href = '../examples/categories.php';
              </script>";
    } else {
        echo "<script>
                alert('Error al guardar los datos.');
                window.history.back();
              </script>";
    }

} else {
    echo "<script>
            alert('Acceso no permitido.');
            window.history.back();
          </script>";
}
?>
