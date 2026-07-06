<?php
session_start();
session_unset(); // Elimina todas las variables de sesión
session_destroy(); // Destruye la sesión

echo 'Sesion cerrada';  // Retorna un mensaje de confirmación
?>
