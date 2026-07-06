<?php
// Conexión a la base de datos
require_once "../connection/db_connection.php";

if ($con->connect_error) {
    die("Conexión fallida: " . $con->connect_error);
}

// Realizar la consulta para obtener los permisos aprobados para la sección
$sql = "
    SELECT *
    FROM contracts_schedule
    WHERE status = 'Aprobado'
    ORDER BY cliente ASC
";

$result = $con->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $con->error);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <link rel="apple-touch-icon" sizes="76x76" href="../assets/img/apple-icon.png">
  <link rel="icon" type="image/png" href="../assets/img/favicon.png">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
  <title>Control de Permisos</title>
  <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0, shrink-to-fit=no' name='viewport' />

  <!--     Fonts and icons     -->
  <link href="https://cdn.jsdelivr.net/gh/creativetimofficial/now-ui-icons/css/now-ui-icons.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css?family=Montserrat:400,700,200" rel="stylesheet" />
  <link href="https://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet"> 
  
  <!-- CSS Files -->
  <link href="../assets/css/bootstrap.min.css" rel="stylesheet" />
  <link href="../assets/css/paper-dashboard.css?v=2.0.1" rel="stylesheet" />
  <link href="../assets/demo/demo.css" rel="stylesheet" />
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
</head>

<body class="">
  <div class="wrapper">
    <div class="sidebar" data-color="white" data-active-color="danger">
    <div class="logo" style="display: flex; align-items: center;">
        <a class="simple-text logo-normal" href="#">
          <img src="../assets/image/WHATSAPP (002).png" alt="Logo" style="height: 50px; margin-right: 10px;">
            Pre-Aprobaciones
        </a>
      </div>
      <div class="sidebar-wrapper">
        <ul class="nav">
          <li><a href="../examples/dashboard.php"> <i class="fas fa-tachometer-alt text-primary"></i> <p>STATUS DE PERMISOS</p></a></li>
          <li><a href="../examples/barcazas.php"> <i class="fas fa-ship text-warning"></i> <p>Barcazas</p></a></li>
          <li><a href="../examples/capitanes_barcazas.php"> <i class="fas fa-user-shield text-success"></i></i><p>Capitanes de Barcazas</p></a></li>
          <li><a href="../examples/tanqueros.php"><i class="fas fa-ship text-danger"></i><p>Tanqueros</p></a></li>
          <li><a href="../examples/inspectores.php"><i class="fas fa-search text-primary"></i><p>Inspectores</p></a></li>
          <li><a href="../examples/cia_inspeccion.php"><i class="fas fa-building text-primary"></i><p>Cia de Inspección</p></a></li>
          <li><a href="../examples/remolcadores.php"><i class="fas fa-anchor text-info"></i><p>Remolcadores</p></a></li>
          <li><a href="../examples/pilotos.php"><i class="fas fa-user-group text-info"></i><p>Pilotos</p></a></li>
          <li><a href="../examples/Contracts_Schedule.php"> <i class="fa fa-book text-info"></i><p>Contracts Schedule</p></a></li>
        </ul>
      </div>
    </div>

    <div class="main-panel">
      <nav class="navbar navbar-expand-lg navbar-absolute fixed-top navbar-transparent">
        <div class="container-fluid">
          <div class="navbar-wrapper">
            <a class="navbar-brand" href="javascript:;"> CONTROL DE PERMISOS </a>
          </div>
        </div>
      </nav>

      <div class="content">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h4 class="card-title text-info"> <b> Contracts Schedule - <script>document.write(new Date().getFullYear())</script> </b> </h4>
                <a href="Contracts_Schedule_Export_PDF.php" class="btn btn-primary"> Descargar PDF</a>


              </div>
              <div class="card-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>Tank</th>
                      <th>Producto</th>
                      <th>Cliente</th>
                      <th>STATUS</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td>" . $row["tank"] . "</td>";
                            echo "<td>" . $row["producto"] . "</td>";
                            echo "<td>" . $row["cliente"] . "</td>";
                            echo "<td>" . $row["status"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='2'>No se encontraron registros</td></tr>";
                    }
                    ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!--   Core JS Files   -->
  <script src="../assets/js/jquery.min.js"></script>
  <script src="../assets/js/popper.min.js"></script>
  <script src="../assets/js/bootstrap.min.js"></script>
  <script src="../assets/js/plugins/perfect-scrollbar.jquery.min.js"></script>
  <script src="../assets/js/ajax.js"></script>
  <script src="../assets/js/plugins/chartjs.min.js"></script>
  <script src="../assets/js/plugins/bootstrap-notify.js"></script>
  <script src="../assets/js/paper-dashboard.min.js?v=2.0.1" type="text/javascript"></script>
  <script src="../assets/demo/demo.js"></script>
</body>

</html>
